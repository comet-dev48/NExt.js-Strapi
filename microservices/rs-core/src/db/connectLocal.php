<?php
    /*$host= 'localhost';
    $db = 'local';
    $user = 'postgres';
    $password = 'Codicesegreto1'; // change to your password

    if(strpos($_SERVER['HTTP_HOST'], 'localhost') === false){
    if(1){
        $host= 'db.rupvyjmpxzoqjajuhhyy.supabase.co';
        $db = 'postgres';
        $user = 'postgres';
        $password = 'Rarespot2022!'; 
    }*/

    //test on new db
    //$host= 'db.ramummfgfukjcstcmbsd.supabase.co';
    /*$host= 'db.pnuviujhzdcdkhyizbjr.supabase.co';
    $db = 'postgres';
    $user = 'postgres';
    $password = 'Codicesegreto1';
 
    try {
        $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
        // make a database connection
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        if ($pdo) {
            echo "Connected to the $db database successfully!";
        }
    } catch (PDOException $e) {
        die($e->getMessage());
    }*/


    //webincostruzione
    /*$host= 'localhost';
    $db = 'webincostruzione_silvio';
    $user = 'webincostruzione_s_user';
    $password = 't35-20bqS8hB';
    try {
        $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
        // make a database connection
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        if ($pdo) {
            echo "Connected to the $db database successfully!";
        }
    } catch (PDOException $e) {
        die($e->getMessage());
    }*/




    //VPS hostinger rarespot.marinisilvio.com
    $host= 'localhost';
    $db = 'rarespot_blockchain';
    $user = 'rarespot_backend';
    $password = 'Zen.Fej78.Ppl745.';
 
    try {
        $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
        // make a database connection
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        /*if ($pdo) {
            echo "Connected to the $db database successfully!";
        }*/
    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>