<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
    ini_set('memory_limit', '-1'); //to avoid Fatal error: Allowed memory size of 134217728 bytes exhausted

    require_once('../vendor/autoload.php');
    include("db/connectLocal.php");

    $client = new \Etherscan\Client('KAU2U4WVWMICTR3WR24KG9J7YDSM2W6KS8');
    $class = "etherscan";


    //retrieve the list of transaction by token identifier (address)
    function getTokenTransaction($tokenId, $startBlock){
        global $client,$pdo,$slogger,$class;

        $res = array(
            "blocks" => 0,
            "lastblock" => 0,
        );

        //validate input
        if (is_null($tokenId)) {
            return false;
        }

        if (is_null($startBlock)) {
            $startBlock = 0;
        } else {
            $startBlock--;
        }

        $result = $client->api('account')->tokenERC721TransferListByAddress(null, $tokenId, null, null, null, $startBlock);

        //print_r(json_encode($result));
        $result = json_decode(json_encode($result));
        if($result->{'status'} == 0){
            $slogger->debug($class , "response: ".$result->{'message'}."\n");
            return $res;
        }

        $in = "INSERT INTO transfers (blockNumber,timeStamp,hash,nonce,blockHash,\"from\",contractAddress,\"to\",tokenId,tokenName,tokenSymbol,tokenDecimal,
        transactionIndex,gas,gasPrice,gasUsed,cumulativeGasUsed,input,confirmations) VALUES ";

        //TODO: Move to db/utils.php and replace logic below
        foreach($result->{'result'} as $tx){
            $in .= "(
                ".$tx->{'blockNumber'}.",
                ".$tx->{'timeStamp'}.",
                '".$tx->{'hash'}."',
                '".$tx->{'nonce'}."',
                '".$tx->{'blockHash'}."',
                '".$tx->{'from'}."',
                '".$tx->{'contractAddress'}."',
                '".$tx->{'to'}."',
                ".pg_escape_string($tx->{'tokenID'}).",
                '".pg_escape_string($tx->{'tokenName'})."',
                '".pg_escape_string($tx->{'tokenSymbol'})."',
                ".$tx->{'tokenDecimal'}.",
                ".$tx->{'transactionIndex'}.",
                ".$tx->{'gas'}.",
                ".$tx->{'gasPrice'}.",
                ".$tx->{'gasUsed'}.",
                ".$tx->{'cumulativeGasUsed'}.",
                '".$tx->{'input'}."',
                ".$tx->{'confirmations'}."
            ),";  

            $res["blocks"]++;
            $res["lastblock"] = $tx->{'blockNumber'};
        }
        
        $in = substr($in, 0, -1);

        $in .= "
             ON CONFLICT (hash,tokenid) DO NOTHING
            ;
        ";
        //$slogger->debug($class , "[INFO] transfers query: ".$in);

        //TODO: evaluate the update of confirmations attribute in case of conflicts (nothing else seems to be changed) 
        //$pdo->query($in);
        
        try {
            $pdo->prepare($in)->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 1062) {
                // TODO: key constraint violation
                throw $e;
            } else {
                throw $e;
            }
        }
        
        return $res;
    }

    //retrieve the list of transaction by account identifier (address)
    function getTx($address, $startBlock){
        global $client,$pdo;

         //validate input
         if (is_null($address)) {
            return false;
        }

        if (is_null($startBlock)) {
            $startBlock = 0;
        }

        $result = $client->api('account')->transactionListByAddress($address, $startBlock, 9999999999, "asc", null, null);
        $res = processTxResult($result, $address, "address");

        return $res;
    }

    //retrieve the transaction details by hash
    function getTxByHash($hash){
        global $client,$pdo,$slogger,$class;

         //validate input
         if (is_null($hash)) {
            return false;
        }

        $result = $client->api('account')->transactionListInternalByHash($hash);        
        if($result['status'] == 1){
            $res = processTxResult($result, $hash, "hash");
        } else {
            $res = zeroUpdateResult($hash);
            $slogger->debug($class , "[WARN] No transactions found for hash ".$hash." - ERROR: ".$result["message"]);
        }
        return $res;
    }

    function zeroUpdateResult($hash) {
        global $client,$pdo;
        $res = array(
            "blocks" => 0,
            "lastblock" => 0
        );

        $txQuery = "INSERT INTO transactions (blocknumber, hash, \"from\", \"to\", value) VALUES (0, '$hash', 'auto', 'auto', 0)";
        try {
            $pdo->prepare($txQuery)->execute();
        } catch (PDOException $e) {
            error_log("[ERROR] Error updating(zero) hash ".$hash." - Code: ".$e->getCode()." - Exception:".$e);
            //throw $e;
        }

        return $res;
    }

    function processTxResult($result, $id, $type){
        global $client,$pdo,$txSchema;
        $result = json_decode(json_encode($result));

        $res = array(
            "blocks" => 0,
            "lastblock" => 0,
        );

        $in = "";
        
        $found = 0;
        foreach($result->{'result'} as $tx){
            $found = 1;
            
            //TODO: move to queryTxBuiler
            $in .= "(";
            $fields = array();

            if($type == "hash"){
                $in .= "'$id',";
                array_push($fields, "hash");
            }
            
            foreach ($txSchema as $key => $value) {
                if(isset($tx->{$key})){
                    array_push($fields, $key);
                    switch ($value) {
                        case 'text':
                            $in .= "'".$tx->{$key}."',";
                            break;
                        case 'int':
                            $in .="".floatval($tx->{$key}).",";
                            break;
                        default:
                            # DO NOTHING
                            break;
                    }
                }
            }
            //TODO: move to queryTxBuiler
            $in = substr($in, 0, -1)."),";

            $res["blocks"]++;
            $res["lastblock"] = $tx->{'blockNumber'};
        }
        $in = substr($in, 0, -1); 
        $txQuery = queryTxBuilder($fields, $in);

        try {
            $pdo->prepare($txQuery)->execute();
        } catch (PDOException $e) {
            // TODO: Exception handler e logger 
            // TODO: key constraint violation  [ $e->getCode() == 1062 ]
            error_log("[ERROR] Error processing address/hash ".$id." - Code: ".$e->getCode()." - Exception:".$e);
            //throw $e;
        }

        return $res;
    }


    //TODO: move to query builder new class
    function queryTxBuilder($fields, $values){
        global $slogger,$class;
        $parsedValues = "";
        foreach ($fields as $value) {
            if($value == "from" || $value == "to" ){
                $parsedValues .= "\"".$value."\"";
            } else {
                $parsedValues .= $value;
            }
            $parsedValues .= ",";
        }
        $parsedValues = substr($parsedValues, 0, -1);
        $res = "INSERT INTO transactions ($parsedValues) VALUES ";
        $res .= $values;
        $res .= "
            ON CONFLICT (hash, \"from\", \"to\", value)
            DO NOTHING
        ";
        /*UPDATE 
        SET 
            value = excluded.value
        WHERE
            transactions.hash = excluded.hash AND 
            transactions.from = excluded.from AND 
            transactions.to = excluded.to
            */
        //$slogger->debug($class , "[DEBUG] Query builder : ".$res);
        return $res;
    }
?>