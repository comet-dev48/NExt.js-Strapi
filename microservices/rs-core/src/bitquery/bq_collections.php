<?php
require_once('../../vendor/autoload.php');
include("../db/connectLocal.php");

include("../db/utils.php");

$slogger = new Slogger;
$class = "bq_collections";

$options = [
    'headers' => [
      'X-API-KEY' => 'BQYLYiT6QyQ4sZgq9wcwnvoWA2EGI3WK',
    ],
  ];

$client = \Softonic\GraphQL\ClientBuilder::build('https://graphql.bitquery.io/', $options);

//Header auth
//X-API-KEY
//BQYLYiT6QyQ4sZgq9wcwnvoWA2EGI3WK

//recupero i dati per la query a bitquery
$offset = 0;
$lastBLockH = 0;
$lastBLockTime = "";

//load start date trought HTTP param
$start_date = isset($_GET["date"]) ? $_GET["date"] : "2020-01-01";
//load start date trought SSH param
$start_date = isset($argv[1]) ? $argv[1] : "2020-01-01";

$st_run = true;
if (isset($argv[2])){
  $offset = $argv[2];
  $st_run = false;
}



if($st_run){
  $sql = 'SELECT lastblockheight, lastblocktimestamp, cumulativeoffset FROM collections_update_handler ORDER BY id DESC LIMIT 1';
  foreach ($pdo->query($sql) as $row) {
      $offset = $row["cumulativeoffset"];
      $lastBLockH = $row["lastblockheight"];
      //$lastBLockTime = $row["lastblocktimestamp"];
  }
}

$slogger->debug($class , "Start handler from date ".$start_date." and offset ".$offset.""); 


// definizione di una query come stringa multiriga
$query = '
{
    ethereum(network: ethereum) {
      smartContractCalls(
        date: {after: "'.$start_date.'"}
        options: {asc: "block.height", limit: 10000, offset: '.$offset.'}
        smartContractMethod: {is: "Contract Creation"}
        smartContractType: {is: Token}
      ) {
        transaction{
          txFrom{
            address
          }
        }
        block {
          height
          timestamp {
            time
          }
        }
        smartContract {
          contractType
          address {
            address
            annotation
          }
          currency {
            name
            symbol
            decimals
            tokenType
          }
        }
      }
    }
  }';


// invocazione del client per eseguire la query
$response = $client->query($query);
// estrazione del risultato
$res_array = $response->getData();
$rowCounter = 0;

$collections = array();
//extract collection values
foreach ($res_array["ethereum"]["smartContractCalls"] as $key => $value) {
    $slogger->debug($class , $value["smartContract"]["currency"]["name"]." (".$value["smartContract"]["currency"]["tokenType"].")");
    $rowCounter++;

    if($value != null && $value["smartContract"]["currency"]["tokenType"] == "ERC721"){
      $new = array(
        "tokenID"=>$value["smartContract"]["address"]["address"],
        "tokenName"=>pg_escape_string($value["smartContract"]["currency"]["name"]),
        "tokenSymbol"=>pg_escape_string($value["smartContract"]["currency"]["symbol"]),
        "lastStatus"=>"CREATED"
      );
      array_push($collections, $new);

      $lastBLockH = $value["block"]["height"];
      $lastBLockTime = $value["block"]["timestamp"]["time"];  
    }
    
}

/* EXAMPLE TOKEN RESULT
 {
   "transaction":{
      "txFrom":{
         "address":"0xe75a28382a9a4c91886fceaa0c199cf5afd16547"
      }
   },
   "block":{
      "height":14480943,
      "timestamp":{
         "time":"2022-03-29 12:00:26"
      }
   },
   "smartContract":{
      "contractType":"Token",
      "address":{
         "address":"0x32fd54bc4b5b005d51e1441a5506269784f24a3b",
         "annotation":null
      },
      "currency":{
         "name":"moopy collection",
         "symbol":"MOOPY",
         "decimals":0,
         "tokenType":"ERC721"
      }
   }
} */

//save to DB
$slogger->debug($class , "saving to db # ".sizeof($collections)." collections \n Till date ".$lastBLockTime."");
if(sizeof($collections) != 0){
  $values="";
  foreach($collections as $c){
    $values .= "('".$c['tokenID']."','".$c['tokenName']."','".$c['tokenSymbol']."','".$c['lastStatus']."' ),";
  }

  $values = substr($values, 0, -1);

  $in = "
  INSERT INTO token_handler (tokenID, tokenName, tokenSymbol, lastStatus)
      VALUES ".$values." 
      ON CONFLICT (tokenID) 
      DO NOTHING
  ";

  //$slogger->debug($class , $in);
  $pdo->prepare($in)->execute();
}

//update handler [only for standard run]
if($st_run){
  $cumulativeRowCounter = $rowCounter + $offset;
  $upd_q = "INSERT INTO collections_update_handler (lastblockheight, cumulativeoffset) VALUES(".$lastBLockH.",".$cumulativeRowCounter.");";
  $pdo->prepare($upd_q)->execute();
}

?>