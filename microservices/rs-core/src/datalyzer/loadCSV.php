<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes

    require_once('../../vendor/autoload.php');
    include("../db/connectLocal.php");

    $debug = false;
    if(isset($_GET["debug"])){
        $debug = true;
    }
    
    $limit = 99999;
    if(isset($_GET["limit"])){
        $limit = $_GET["limit"];
    }

    $csvFilePath = "RARESPOT_upload_batch_001.csv";
    $file = "";
    if(file_exists($csvFilePath)){
        $file = fopen($csvFilePath, "r");
    } else {
        echo "Impossibile aprire il file";
        exit;
    }
    
    
    $i=0;
    while (($row = fgetcsv($file)) !== FALSE) {
        $stmt = $pdo->prepare("INSERT INTO descriptions (tokenid, description) VALUES (?, ?)");
        $par = explode(";",$row[0],2);
        $tokenid = htmlentities($par[0], ENT_QUOTES, "UTF-8");
        $desc = htmlentities($par[1], ENT_QUOTES, "UTF-8");
        //0xc70be5b7c19529ef642d16c10dfe91c58b5c3bf0


        if($debug){
            echo "Adding ".$tokenid." and ".$desc."<br/>";
        }
        try {
            $stmt->execute([$tokenid, $desc]);
        } catch (\Throwable $th) {
            //DO NOTHING
            if($debug){
                echo "Error inserting row: $th <br/>";
            }
        }
        
        //print_r($stmt);
        $i++;
        if($i>$limit){
            break;
        }
    }
    echo "File imported succesfully!";
?>