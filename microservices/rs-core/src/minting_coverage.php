<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    //require_once('config.php');
    include("db/connectLocal.php");
    include("db/utils.php");
    include("etherscan.php");

    $slogger = new Slogger;
    $class = "minting_coverage";

    $sql = '
        SELECT t.hash AS hash 
        FROM transfers t
        LEFT JOIN transactions tx ON t.hash = tx.hash
        WHERE 
            value IS NULL
            AND t.from = \'0x0000000000000000000000000000000000000000\'
    ';

    $i = 0;
    $txQuery = "INSERT INTO transactions (blocknumber, hash, \"from\", \"to\", value) VALUES";
    foreach ($pdo->query($sql) as $row) {
        //$slogger->debug($class , "Processing transfer:".$row["hash"]." for whitelist auto tx creation");
        $txQuery .= "(0, '".$row["hash"]."', 'auto', 'auto', 0),";
        $i++;
    }

    $txQuery = substr($txQuery,0,-1);
    $txQuery .= "
        ON CONFLICT (hash, \"from\", \"to\", value)
        DO NOTHING
    ";

    if($i > 0){
        try {
            $pdo->prepare($txQuery)->execute();
            $slogger->debug($class , "Minting tx rows added: ".$i." \n");
        } catch (PDOException $e) {
            error_log("[ERROR] Error updating(zero) for minting - Code: ".$e->getCode()." - Exception:".$e);
            //throw $e;
        }
    } else {
        $slogger->debug($class , "No tx to be added - SKIP \n");
    }
    




?>