<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    //require_once('config.php');
    include("db/connectLocal.php");
    include("db/utils.php");
    include("etherscan.php");

    $slogger = new Slogger;
    $class = "recover_tx_handler";

    $address = "";
    $cntTx = 0;
    $res = array();
    $limit = 10000;

    $hash ="";
    $sql = '
        SELECT t.hash as hash, t.to as address
        FROM transfers t
        LEFT JOIN transactions l ON t.hash = l.hash
        WHERE value IS NULL
        LIMIT 1
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $hash = $ad["hash"];
        $address = $ad["address"];
    }
    if($hash != ""){
            
        $slogger->debug($class ,"RECOVER Calling getTxByHash for hash = $hash of address = $address");
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