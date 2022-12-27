<?php
    include("rs-core/src/db/connectLocal.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Rarespot Server</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime&display=swap" rel="stylesheet">


    <style type="text/css" media="screen">
  * {
    margin: 0px 0px 0px 0px;
    padding: 0px 0px 0px 0px;
  }

  body, html {
    padding: 3px 3px 3px 3px;

    background-color: #D8DBE2;

    font-family: 'Courier Prime', monospace;
    font-size: 11pt;
    text-align: center;
  }

  div.main_page {
    position: relative;
    display: table;

    width: 800px;

    margin-bottom: 3px;
    margin-left: auto;
    margin-right: auto;
    padding: 0px 0px 0px 0px;

    border-width: 2px;
    border-color: #212738;
    border-style: solid;

    background-color: #FFFFFF;

    text-align: center;
  }

  div.page_header {
    height: 199px;
    width: 100%;

    background-color: #F5F6F7;
  }

  div.page_header span {
    margin: 15px 0px 0px 50px;

    font-size: 180%;
    font-weight: bold;
  }

  div.page_header img {
    margin: 3px 0px 0px 40px;

    border: 0px 0px 0px;
  }

  div.table_of_contents {
    clear: left;

    min-width: 200px;

    margin: 3px 3px 3px 3px;

    background-color: #FFFFFF;

    text-align: left;
  }

  div.table_of_contents_item {
    clear: left;

    width: 100%;

    margin: 4px 0px 0px 0px;

    background-color: #FFFFFF;

    color: #000000;
    text-align: left;
  }

  div.table_of_contents_item a {
    margin: 6px 0px 0px 6px;
  }

  div.content_section {
    margin: 3px 3px 3px 3px;

    background-color: #FFFFFF;

    text-align: left;
  }

  div.content_section_text {
    padding: 4px 8px 4px 8px;

    color: #000000;
    font-size: 100%;
  }

  div.content_section_text pre {
    margin: 8px 0px 8px 0px;
    padding: 8px 8px 8px 8px;

    border-width: 1px;
    border-style: dotted;
    border-color: #000000;

    background-color: #F5F6F7;

    font-style: italic;
  }

  div.content_section_text p {
    margin-bottom: 6px;
  }

  div.content_section_text ul, div.content_section_text li {
    padding: 4px 8px 4px 16px;
  }

  div.section_header {
    padding: 3px 6px 3px 6px;

    background-color: #8E9CB2;

    color: #FFFFFF;
    font-weight: bold;
    font-size: 112%;
    text-align: center;
  }

  div.section_header_red {
    background-color: #CD214F;
  } 
  
  div.section_header_rare {
    background-color: #05d5b0;
  } 
  

  div.section_header_grey {
    background-color: #9F9386;
  }

  .floating_element {
    position: relative;
    float: left;
  }

  div.table_of_contents_item a,
  div.content_section_text a {
    text-decoration: none;
    font-weight: bold;
  }

  div.table_of_contents_item a:link,
  div.table_of_contents_item a:visited,
  div.table_of_contents_item a:active {
    color: #000000;
  }

  div.table_of_contents_item a:hover {
    background-color: #000000;

    color: #FFFFFF;
  }

  div.content_section_text a:link,
  div.content_section_text a:visited,
   div.content_section_text a:active {
    background-color: #DCDFE6;

    color: #000000;
  }

  div.content_section_text a:hover {
    background-color: #000000;

    color: #DCDFE6;
  }

  div.validator {
  }

  td {
    padding: 5px;
}

#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #8E9CB2;
  color: white;
}

li {
    margin-left: 20px;
}
    </style>
  </head>
  <body>
    <div class="main_page">
      <div class="page_header floating_element">
        <img src="Logo-server.png" alt="Rarespot Logo" class="floating_element" style="width:50%; margin:35px; margin-left: 25%;"/>
      </div>
      <div class="content_section floating_element">


        <div class="section_header section_header_rare">
          <div id="about"></div>
          Collections job monitor
        </div>
        <p style="margin:5px;">
                Server time: <?php echo date('m/d/Y H:i:s'); ?> <br/>
                Status: 
                <ul> 
                    <li> SUCCESS (ok, historical data under update) </li> 
                    <li> COMPLETED (ok, historical data updated, the real-time data under update) </li>
                </ul>
          </p>
        <div class="content_section_text">
            <table id="customers">
                <tr>
                    <th> Token</th>
                    <th> Symbol</th>
                    <th> l-block</th>
                    <th> l-timestamp</th>
                    <th> l-status</th>
                </tr>

            <?php
                $sql = '
                    SELECT tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel  
                    from token_handler
                    ;
                ';
            foreach ($pdo->query($sql) as $row) {
                    $date = date('m/d/Y H:i:s', $row['lastupdatetimestamp']);
                    echo "
                        <tr>
                            <td> ".$row['tokenname']." </td>
                            <td> ".$row['tokensymbol']." </td>
                            <td> ".$row['lastblocknumber']." </td>
                            <td> ".$date." </td>
                            <td> ".$row['laststatus']." </td>
                        </tr>
                    ";
                }
            ?>
            </table>
        </div>

        <div class="section_header section_header_rare" style="margin-top: 20px;">
          <div id="about"></div>
          Transactions job monitor
        </div>
        <div class="content_section_text">
        <p style="margin:5px;">
                Only collections that already have some transactions registered are displayed.
          </p>
            <table id="customers">
                <tr>
                    <th> Token name</th>
                    <th> Transfers </th>
                    <th> Volume </th>
                </tr>

            <?php
                $sql = '
                    SELECT tokenName, count(*) as tx, sum(value) as volume
                    FROM transactions tx
                    JOIN transfers t ON tx.hash = t.hash
                    GROUP BY tokenname;
                
                ';
            foreach ($pdo->query($sql) as $row) {

                    echo "
                        <tr>
                            <td> ".$row['tokenname']." </td>
                            <td> ".$row['tx']." </td>
                            <td> ".($row['volume']/1000000000000000000)." ETH </td>
                        </tr>
                    ";
                }
            ?>
            </table>
        </div>


        <div class="section_header" style="margin-top: 20px;">
          <div id="about"></div>
          Cronjobs monitor
        </div>
        <div class="content_section_text">
        <p style="margin:5px;">
                Data collected from logs. 
          </p>
            <table id="customers">
                <tr>
                    <th> Cronjob</th>
                    <th> Last run </th>
                    <th> Last status </th>
                    <th> Last target </th>
                    <th> Target count </th>
                </tr>

            <?php
                
            ?>
            </table>
        </div>

      </div>
    </div>
    <div class="validator">
    </div>
  </body>
</html>

