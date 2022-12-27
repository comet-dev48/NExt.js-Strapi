<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    //require_once('config.php');
    include("db/connectLocal.php");
    include("db/utils.php");
    include("etherscan.php");

    $slogger = new Slogger;
    $class = "ether_handler";

    $tokenId = "";
    $tokenName = ""; //logging
    $lastBlock = 0;

    $sql = '
        SELECT id,tokenid,tokenname, lastblocknumber, lastupdatetimestamp, laststatus, prioritylevel 
        FROM token_handler
    ';
    //WHERE laststatus != \'COMPLETED\'
    $currBlock = 9999999999999999999;
    $currLastTime = 9999999999999999999;
    foreach ($pdo->query($sql) as $row) {
        $score = 0; //TODO calculate based on lastupdate + prio
        //$slogger->debug($class , $row["tokenname"]." with score ".$score." \n";;

        if($row["lastupdatetimestamp"] <= $currLastTime){
            $tokenId = strtolower($row["tokenid"]);
            $tokenName = $row["tokenname"];

            $lastBlock = $row["lastblocknumber"] != null ? $row["lastblocknumber"] : 0;
            $currBlock = $lastBlock;

            $lastTimestamp = $row["lastupdatetimestamp"] != null ? $row["lastupdatetimestamp"] : 0;
            $currLastTime = $lastTimestamp;
        }
    }

    $slogger->debug($class , "Selected_token:\"".$tokenName."\" - address:\"".$tokenId."\"");

    $res = getTokenTransaction($tokenId, $lastBlock);

    $lastStatus = "SUCCESS";
    if($res["blocks"] < 9999){
        $lastStatus = "COMPLETED";
        //TODO: review logic to updated completed collections after X days (or other limit)
    }
    $slogger->debug($class , "saved_result=".$res["blocks"]." - last_block=".$res["lastblock"]." - last_status=\"".$lastStatus."\"");


    if($res["blocks"] >= 0){
        $date = new DateTime();
        $in = "
            UPDATE token_handler
            SET 
                lastblocknumber = ".$res["lastblock"].",
                lastupdatetimestamp = ".$date->getTimestamp().",
                laststatus = '".$lastStatus."'
            WHERE tokenid = '$tokenId'; 
        ";
        $slogger->debug($class , $in);
        $pdo->prepare($in)->execute();
        //$slogger->debug($class , "Updated \n";
    } else {
        $slogger->debug($class , "update FAILED \n");
    }
    
    
    


?>