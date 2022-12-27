<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    //require_once('config.php');
    include("db/connectLocal.php");
    include("db/utils.php");
    include("etherscan.php");

    $slogger = new Slogger; 
    $class = "ether_tx";

    $address = "";
    $cntTx = 0;
    $res = array();
    $limit = 10000;

    //TODO: move to DB
    //TODO: blacklisted address handler
    $blacklist = '
        \'0x5b3256965e7C3cF26E11FCAf296DfC8807C01073\',
        \'0xE052113bd7D7700d623414a0a4585BCaE754E9d5\',
        \'0x0a267cF51EF038fC00E71801F5a524aec06e4f07\',
        \'0x7Be8076f4EA4A4AD08075C2508e481d6C946D12b\',
        \'0xcDA72070E455bb31C7690a170224Ce43623d0B6f\',
        \'0xb1690c08e213a35ed9bab7b318de14420fb57d8c\'
    ';

    //0xb1690c08e213a35ed9bab7b318de14420fb57d8c temporary added

    //TODO: COMPLETED address handling is missing

    $sql = '
        SELECT tok.to as address, count(*) as cnttx from transfers tok
            LEFT JOIN transactions tl ON tok.hash = tl.hash
            LEFT JOIN transactions_handler th ON tok.to = th.address 
            WHERE tok.from != \'0x0000000000000000000000000000000000000000\'
            AND (th.lastStatus IS NULL OR th.lastStatus = \'SUCCESS\')
            AND tok.to NOT IN ('.strtolower($blacklist).')
            GROUP BY tok.to ORDER BY cnttx DESC;
    ';
    //value IS NULL AND
    foreach ($pdo->query($sql) as $row) { 

        //TODO: aggiungere controllo sul last-updated-address 
        //se l'ultima chiamata ha aggiornato 10K record può ripetere lo stesso indirizzo
        //altrimenti non lo considera e lo aggiunge ad una blacklist

        if($cntTx < $row["cnttx"]){
            $address = $row["address"];
            $cntTx = $row["cnttx"];
        }
    }

    $slogger->debug($class , "Highest score for \"".$address."\" with ".$cntTx." transactions \n");
    //$slogger->debug($class ,"[INFO] Updating:".$address);

    /*replace address to test for specific address
    $address = "0x3ff5c610d31fe4ba4031b472754fb1310d052384";
    $slogger->debug($class , "<br/> Replacing address with ".$address." <br/>"); */

    $lastBlock = 0;

    if($cntTx < 3){
        $hash ="";
        $sql = "
            SELECT hash 
            FROM transfers
            WHERE \"to\" = '$address' 
            LIMIT 1
        ";
        $stmt = $pdo->query($sql);
        $ad = $stmt->fetch();
        if($ad != null){
            $hash = $ad["hash"];
        }
        $slogger->debug($class ,"Calling getTxByHash for hash = $hash");
        $res = getTxByHash($hash);

        if($res != null){
            if($res["blocks"] == 1){
                $slogger->debug($class , "Tx added, saved_result=".$res["blocks"]."\n");
            } else {
                if($res["blocks"] == 0){
                    $slogger->warn($class , "Multiple transactions found for hash=".$hash.", saved_result=".$res["blocks"]."\n");
                } else {
                    $slogger->warn($class , "No Tx found for hash=".$hash.", saved_result=".$res["blocks"]."\n");
                    //TODO update with 0 ?
                }
            }
        } 

    } else {
        $sql = "
            SELECT lastBlockNumber, lastUpdateTimestamp, lastStatus 
            FROM transactions_handler
            WHERE address = '$address' 
            LIMIT 1
        ";
        $stmt = $pdo->query($sql);
        $ad = $stmt->fetch();
        if($ad != null){
            $lastBlock = $ad["lastblocknumber"];
        }
        
        $slogger->debug($class ,"Calling getTx for address = $address and start block = $lastBlock");
        $res = getTx($address, $lastBlock);
        $slogger->debug($class , "saved_result=".$res["blocks"]." - last_block=".$res["lastblock"]."\n");

        if($res != null){
            $upd_res = '';
            if($res["blocks"] > 0){
                $upd_res = 'SUCCESS';
            }

            if($res["blocks"] < $limit){
                $upd_res = 'COMPLETED';
            }
        } else {
            $upd_res = 'SKIP';
            $res["blocks"] = 0;
            $res["lastblock"] = 0;
        }

        $date = new DateTime();

        
        $in = "
        INSERT INTO transactions_handler (address, lastBlockNumber, lastUpdateTimestamp, lastStatus)
            VALUES('$address', ".$res["lastblock"]." ,".$date->getTimestamp().", '$upd_res' ) 
            ON CONFLICT (address) 
        DO 
            UPDATE 
            SET 
                lastblocknumber = ".$res["lastblock"].",
                lastupdatetimestamp = ".$date->getTimestamp().",
                laststatus = '$upd_res' 
        ";
        $pdo->prepare($in)->execute();
        $slogger->debug($class , "Address:\"".$address."\" updated with status \"".$upd_res."\"");
    }
?>