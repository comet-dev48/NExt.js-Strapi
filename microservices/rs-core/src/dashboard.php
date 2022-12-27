<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

    require_once('../vendor/autoload.php');
    include("db/connectLocal.php");

    $totalNotMinted = $totalTokenTransfer = 0;

    //token
    $sql = 'SELECT count(*) as cnt from transfers';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $totalTokenTransfer = $ad["cnt"];
        echo "<br/> # ERC-721 transfers (NFT) : ".$totalTokenTransfer. "<br/>";
    }

    //transactions
    $sql = 'SELECT count(*) as cnt from transactions';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        echo " # ERC-20 transfers (ETH) : ".$ad["cnt"]. "<br/>";
    }

    echo "<br/> Transfer coverage: <br/>";
    echo "<br/> - Minted: <br/>";

    //Mint
    $sql = '
        SELECT count(*) AS cnt 
        from transfers tok
        LEFT JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE tok.to = \'0x0000000000000000000000000000000000000000\' OR tok.from = \'0x0000000000000000000000000000000000000000\';
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $totalNotMinted = $totalTokenTransfer-$ad["cnt"];
        echo " # token minted : ".$ad["cnt"]." -- ".($ad['cnt']/$totalTokenTransfer*100)." % <br/>";
    }

    echo "<br/> - Transfered: <br/>";

    //token with value
    $sql = '
        SELECT count(*) AS cnt 
        from transfers tok
        JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE value IS NOT NULL
        AND tok.to != \'0x0000000000000000000000000000000000000000\' AND tok.from != \'0x0000000000000000000000000000000000000000\';
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        echo " # token transfers with value : ".$ad["cnt"]." -- ".($ad['cnt']/$totalNotMinted*100)." % <br/>";
    }

    //token with value
    $sql = '
        SELECT count(*) AS cnt 
        from transfers tok
        JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE value = 0 AND tok.to != \'0x0000000000000000000000000000000000000000\' AND tok.from != \'0x0000000000000000000000000000000000000000\';;
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        echo " # token transfers with 0 value : ".$ad["cnt"]." -- ".($ad['cnt']/$totalNotMinted*100)." % <br/>";
    }

    //token with value
    $sql = '
        SELECT count(*) AS cnt 
        from transfers tok
        LEFT JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE value IS NULL AND tok.to != \'0x0000000000000000000000000000000000000000\' AND tok.from != \'0x0000000000000000000000000000000000000000\';;
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        echo " # token transfers with NO value : ".$ad["cnt"]." -- ".($ad['cnt']/$totalNotMinted*100)." % <br/>";
    }

    //Addresses
    echo "<br/> Addresses coverage: <br/>";
    $totalAddress = 0;
    $sql = '
        SELECT count(DISTINCT(tok.to)) as cnt FROM transfers tok
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $totalAddress = $ad["cnt"];
        echo "- # Distinct : $totalAddress <br/>";
    }

    $sql = '
        SELECT count(DISTINCT(address)) as cnt FROM transactions_handler;
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        echo "- # Processed : ".$ad["cnt"]." <br/>";
        echo "- Coverage : ".($ad["cnt"]/$totalAddress*100)." %";
    }


    //collection list
    echo "<br/><br/>Token transfers by collection: <br/>";
    $sql = '
        SELECT tokenname, count(*) AS cnt 
        from transfers 
        GROUP BY tokenname
        ;
    ';
    echo "<table>
        <tr> 
            <th> Token name </th>
            <th> # </th>
            <th> Cov% </th>
        </tr>";
    foreach ($pdo->query($sql) as $row) {
        echo "<tr>
                <td> ".$row["tokenname"]." </td>
                <td> ".$row["cnt"]." </td>
                <td> ... </td>
            </tr>
        ";
    }
    echo "</table>";


    echo "<h2> Azuki Data validation </h2>";
    $supply = $volume = $trade = $holders = 0;

    $sql = '
        SELECT count(DISTINCT(tokenid)) as cnt FROM transfers tok
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $supply = $ad["cnt"];
    }

    $sql = '
        SELECT SUM(value) as sumv FROM transactions;
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $volume = $ad["sumv"] / 1000000000000000000;
    }

    $sql = '
        SELECT COUNT(DISTINCT(addr)) AS holders FROM (
            SELECT DISTINCT(tokenid), MAX("to") AS addr  FROM transfers GROUP BY tokenid ORDER BY tokenid 
        ) AS x;
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $holders = $ad["holders"];
    }

    $sql = '
        SELECT MIN(value) AS floor FROM transactions WHERE to_timestamp(timestamp)::date = (
            SELECT MAX(to_timestamp(timestamp)::date) FROM transactions
        ) AND value > 0
    ';
    $stmt = $pdo->query($sql);
    $ad = $stmt->fetch();
    if($ad != null){
        $floor = $ad["floor"]/ 1000000000000000000;
    }


    echo "<table style='width:500px;'>
            <tr> 
                <td> Index </td>
                <td> Value </td>
                <td> Expected value at 31/4 </td>
                <td> Status </td>
            </tr>
            <tr>
                <td> Supply </td>
                <td> ".$supply." </td>
                <td> 10K </td>
                <td style='color:green'> OK </td>
            </tr>
            <tr>
                <td> Transfers </td>
                <td> ".$totalTokenTransfer." </td>
                <td> 50K </td>
                <td style='color:green'> OK </td>
            </tr>
            <tr>
                <td> Volume </td>
                <td> ".$volume." ETH</td>
                <td> 200K ETH </td>
                <td style='color:orange'> Investigation </td>
            </tr>
            <tr>
                <td> Holders </td>
                <td> ".$holders." </td>
                <td> 5,312 </td>
                <td style='color:green'> OK </td>
            </tr>
            <tr>
                <td> Floor price </td>
                <td> ".$floor." </td>
                <td>  </td>
                <td style='color:orange'> Sto sbagliando il calcolo! </td>
            </tr>
        </table>";

?>

<STYLE>
    table {
        border: 1px solid black; 
    }

    td {
        border: 0px solid black;
    }
</STYLE>