<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    //require_once('config.php');
    include("db/connectLocal.php");
    include("db/utils.php");
    include("etherscan.php");

    $slogger = new Slogger;
    $class = "marketplaces_reviewer";

    $address = "";
    $cntTx = 0;
    $res = array();
    $limit = 10000;

    //TODO: move to DB
    //TODO: marketplacesed address handler
    //TODO: identify a strategy to add addresses to marketplaces
    $marketplaces = '
        \'0x7be8076f4ea4a4ad08075c2508e481d6c946d12b\' 
    ';

    //1 - //openSea

    $hash ="";
    $sql = '
        SELECT hash, count(*) as c
        FROM transactions 
        WHERE hash IN (
            SELECT hash FROM transactions 
            WHERE 
            (
                "to" IN ('.strtolower($marketplaces).')  
                OR "from" IN ('.strtolower($marketplaces).')
            )
            AND 
            (
                reprocessed IS NULL 
                OR reprocessed = 0
            )
        )
        GROUP BY hash
        HAVING count(*) = 1
        ORDER BY c ASC;
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    
    if($ad != null && $ad["c"] < 2){
        $hash = $ad["hash"];
        $slogger->debug($class , "Tx $hash has ".$ad["c"]." and must be reprocessed");
    }
    if($hash != ""){
            
        $slogger->debug($class ,"Reprocessing hash = $hash ");
        $res = getTxByHash($hash);

        if($res != null){
            if($res["blocks"] == 1){
                $slogger->debug($class , "Results: ".$res["blocks"]." - No new transactions found, Updating row \n");

                //update reprocessed attribute to skip this row next time
                $q = "
                    UPDATE transactions 
                        SET reprocessed = 1
                    WHERE hash = '".$hash."';
                ";
                $pdo->query($q);
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