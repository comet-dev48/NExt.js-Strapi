<?php
    $http_origin = $_SERVER['HTTP_HOST'];

    if ($http_origin == "rarespot.io" OR $http_origin == "10.29.6.74")
    {  
        header("Access-Control-Allow-Origin: $http_origin");
    }

    $class = "subscribe.php";

    if(isset($_GET["mail"])){
        $message = $_GET["mail"];
        try {
            $fp = fopen('subscribed.txt', 'a');
        } catch (\Throwable $th) {
            //Do nothing
        }

        //TODO add debug level logic
        if($fp != NULL){
            //removing all newline from the message
            $message = preg_replace("/\r|\n/", "", $message);
            $message = preg_replace('/\s+/', ' ', $message);

            $ms =  "[".date("Y-m-d H:i:s")."] | $message \n";
            fwrite($fp, $ms);

            fclose($fp); 

            http_response_code(200);
            echo json_encode(array("message" => "mail added"));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Invalid input"));
    }


?>