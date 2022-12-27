<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    //require_once('config.php');
    include("db/connectLocal.php");
    include("db/utils.php");
    include("etherscan.php");

    $slogger = new Slogger;
    $class = "blacklisted_tx_handler";

    $address = "";
    $cntTx = 0;
    $res = array();
    $limit = 10000;

    //TODO: move to DB
    //TODO: blacklisted address handler
    //TODO: identify a strategy to add addresses to blacklist
    $blacklist = '
        \'0x5b3256965e7C3cF26E11FCAf296DfC8807C01073\',
        \'0xE052113bd7D7700d623414a0a4585BCaE754E9d5\',
        \'0x0a267cF51EF038fC00E71801F5a524aec06e4f07\',
        \'0x7Be8076f4EA4A4AD08075C2508e481d6C946D12b\',
        \'0xcDA72070E455bb31C7690a170224Ce43623d0B6f\'
    ';

    $hash ="";
    $sql = '
        SELECT t.hash as hash, t.to as address
        FROM transfers t
        LEFT JOIN transactions l ON t.hash = l.hash
        WHERE t.to IN ('.strtolower($blacklist).') 
        AND value IS NULL
        LIMIT 1
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $hash = $ad["hash"];
        $address = $ad["address"];
    }
    if($hash != ""){
            
        $slogger->debug($class ,"BL Calling getTxByHash for hash = $hash of address = $address");
        $res = getTxByHash($hash);

        if($res != null){
            if($res["blocks"] == 1){
                $slogger->debug($class , "Tx $hash added, saved_result=".$res["blocks"]."\n");
            } else {
                if($res["blocks"] == 0){
                    $slogger->warn($class , "No Tx found for hash=".$hash.", Updating with 0");
                } else {
                    $slogger->warn($class , "Multiple transactions found for hash=".$hash.", saved_result=".$res["blocks"]."\n");
                    //TODO update with 0 ?
                }
            }
        }
    } else {
        $slogger->warn($class ,"No transfer found with query: $sql");
    } 
?>