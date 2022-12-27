<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes

    require_once('../../vendor/autoload.php');
    include("../db/connectLocal.php");

    $debug = false;
    if(isset($_GET["debug"])){
        $debug = true;
    }

    $txDebug = false;
    $txLimit = 10;
    if(isset($_GET["txDebug"])){
        $txDebug = true;
        $txLimit = $_GET["txLimit"];
    }

    //recupero i dati da splittare

    /*  
        final values per date
        DONE: 
            transfers
            volume
            avg_price
            minters * 
            floor_price *
            
        MOVE to LATE CALC:
            market_cap = floor_price * circulating_supply
            market_cap_eth
            floor_price_change
            avg_price_change
            volume_change
        
        MOVE TO collection info:
            circulating_supply
            owners
    */


    //TODO: ciclo le diverse collezioni 
    // Azuki: $collection = "0xED5AF388653567Af2F388E6224dC7C4b3241C544";
    $collection = "0xd4e4078ca3495de5b1d4db434bebc5a986197782";

    if($debug){
        echo "<table>";
        echo "<tr><th>Date</th><th>Collection ID</th><th>Total Transfers</th><th>Volume</th><th>AVG Value</th><th>Minters</th><th>floor_price</th> </tr>";
    }

    //dati per singola collezione
    $valueList = "";
    $qry = "
        SELECT 
            to_timestamp(tr.timestamp)::date as date, SUM(value) as totalvalue , 
            AVG(value) as avgvalue, COUNT(*) as totaltransfer,
            count(CASE WHEN tr.from = '0x0000000000000000000000000000000000000000' THEN 1 END) as minters,
            MIN(CASE WHEN value > 0 THEN value END) as floor_price
        FROM transfers tr 
        LEFT JOIN transactions txl
        ON tr.hash = txl.hash
        WHERE contractaddress = '".strtolower($collection)."'
        GROUP BY to_timestamp(tr.timestamp)::date
        ORDER BY to_timestamp(tr.timestamp)::date ASC
    ";
    foreach ($pdo->query($qry) as $row) {
        $valueList .= "('".$row["date"]."','".$collection."',".$row["totaltransfer"].",".$row["totalvalue"].",".$row["avgvalue"].",".$row["minters"].",".$row["floor_price"]."),";
        if($debug){
            echo "<tr>
                <td>".$row["date"]."</td>
                <td>".$collection."</td>
                <td>".$row["totaltransfer"]."</td>
                <td>".($row["totalvalue"]/1000000000000000000)."</td>
                <td>".($row["avgvalue"]/1000000000000000000)."</td> 
                <td>".$row["minters"]."</td> 
                <td>".($row["floor_price"]/1000000000000000000)."</td> 
            </tr>";
        }
    }

    if($valueList != ""){
        $valueList = substr($valueList,0,-1);
        //Insert aggregated data
        $aqr = "INSERT INTO aggregated_by_day (date, collection, totaltransfer, totalvalue, avgvalue)
            VALUES
                ".$valueList."
            ON CONFLICT (date,collection) DO UPDATE 
                SET totaltransfer = excluded.totaltransfer,
                    totalvalue = excluded.totalvalue,
                    avgvalue = excluded.avgvalue;
        ";
        //$pdo->query($aqr);
    }
    
    if($debug){
        echo "</table>";
    }

    echo "<hr/><br/>";

    if($txDebug){
        if($debug){
            echo "<table>";
            echo "<tr>
                <th>Date</th>
                <th>Time ID</th>
                <th>Hash</th>
                <th>Collection</th>
                <th>Value</th>
            </tr>";
        }
    
        //dati per singola collezione
        $valueList = "";
        $qry = "
            SELECT 
                to_timestamp(tr.timestamp)::date as date, to_timestamp(tr.timestamp)::time as time, tr.hash as hash, value          
            FROM transfers tr 
            LEFT JOIN transactions txl
            ON tr.hash = txl.hash
            WHERE value IS NOT null AND contractaddress = '".strtolower($collection)."'
            ORDER BY tr.timestamp desc
            LIMIT $txLimit
        ";
        foreach ($pdo->query($qry) as $row) {
            //$valueList .= "('".$row["date"]."','".$collection."',".$row["totaltransfer"].",".$row["totalvalue"].",".$row["avgvalue"].",".$row["minters"].",".$row["floor_price"]."),";
            if($debug){
                echo "<tr>
                    <td>".$row["date"]."</td>
                    <td>".$row["time"]."</td>
                    <td>".$row["hash"]."</td>
                    <td>".$collection."</td>
                    <td>".($row["value"]/1000000000000000000)."</td> 
                </tr>";
            }
        }

        if($debug){
            echo "</table>";
        }
    
        echo "<hr/><br/>";
    }

?>

<style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr:nth-child(even) {
    background-color: #dddddd;
    }
</style>