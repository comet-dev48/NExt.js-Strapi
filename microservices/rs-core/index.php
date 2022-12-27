<?php
require_once('vendor/autoload.php');
require_once('config.php');

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;

$debug = false;

// Register the function with Functions Framework.
// This enables omitting the `FUNCTIONS_SIGNATURE_TYPE=http` environment
// variable when deploying. The `FUNCTION_TARGET` environment variable should
// match the first parameter.
FunctionsFramework::http('bqLoader', 'bqLoader');

function bqLoader(ServerRequestInterface $request): string
{
    $local = strpos("localhost", $_SERVER['SERVER_NAME']) !== false ? true : false;
    /*if(!$local){ 
        include("connect.php"); 
    } else {
        include("connectLocal.php");
    }*/
    $name = 'World';
    $body = $request->getBody()->getContents();
    if (!empty($body)) {
        $json = json_decode($body, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf(
                'Could not parse body: %s',
                json_last_error_msg()
            ));
        }

        //TODO parameters handler
        $name = $json['name'] ?? $name;
    }
    $queryString = $request->getQueryParams();

    //function code
    $name = $queryString['name'] ?? $name;
    
    $btresponse = graphBitQuery();

    //elaborate response
    $response = "Test response handler - ";

    print_r(json_encode($btresponse["ethereum"]));

    if($pdo != null){
        //insert something to the DB
        $query = "INSERT INTO entries (guestName, content) values ('third guest', 'Me too too!');";
        $pdo->query($query);

        $stmt = $pdo->prepare("SELECT * FROM entries");
        $stmt->execute();
        // using while
        while($row = $stmt->fetch()) {
            $response .= $row['guestname'];
        }
    } else {
        $response .= "analyzing response - ";
    }

    return false;
}


function graphBitQuery(){
    include("queries.php");
    $options = [
        'headers' => [
          'X-API-KEY' => 'BQYLYiT6QyQ4sZgq9wcwnvoWA2EGI3WK',
        ],
      ];
    
    $client = \Softonic\GraphQL\ClientBuilder::build('https://graphql.bitquery.io/', $options);
    
    //Header auth
    //X-API-KEY
    //BQYLYiT6QyQ4sZgq9wcwnvoWA2EGI3WK
    
    // definizione di una query come stringa multiriga
    $query = $queries["collection_info"];
    if($GLOBALS["debug"]){ echo "calling query: ".$query."<br/><br/>"; } 
    $param = $params["collection_info"];

    // invocazione del client per eseguire la query
    $response = $client->query($query, $param);
    // estrazione del risultato
    return $response->getData();
}