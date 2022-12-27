<?php

    $txSchema = array(
        "blockNumber" => "int",
        "timeStamp" => "int",
        "hash" => "text",
        "nonce" => "text",
        "blockHash" => "text",
        "from" => "text",
        "to" => "text",
        "value" => "int",
        "gas" => "int",
        "gasPrice" => "int",
        "cumulativeGasUsed" => "int",
        "isError" => "int",
        "txreceipt_status" => "int",
        "input" => "int",
        "confirmations" => "int"
    );

    //IMPORTANT: disable logging before production deployment
    $debugEnabled = true;

    class Slogger {

        function slog($class, $level, $message){
            global $debugEnabled;

            //TODO add debug level logic
            if($debugEnabled){
                //removing all newline from the message
                $message = preg_replace("/\r|\n/", "", $message);
                $message = preg_replace('/\s+/', ' ', $message);

                echo "[RS-$level] [".date("Y-m-d H:i:s")."] [$class]:  $message \n";
            }
        }

        function debug($class, $message){
            $this->slog($class, "DEBUG", $message);
        }

        function info($class, $message){
            $this->slog($class, "INFO", $message);
        }

        function warn($class, $message){
            $this->slog($class, "WARN", $message);
        }
    }

    class Utility {
        function arrayToString(array $array){
            $result = "";
            foreach ($array as $key => $value){
                $result .= $key."->".$value.", ";
            }
            return rtrim($result, ", ");
        }

        function isAdmin(){
            global $user;
            //$slogger->debug($class, "Checking level for user = ".$user["username"].", level = ".$user["livello"]);
            if($user["livello"] == "admin"){
                return true;
            } else {
                return false;
            }
        }

        function datetimeToString($ore){
            $result = "";
            switch ($ore) {
                case 0:
                    $result = "-";
                    break;
                case 1:
                    $result = "1 ora";
                    break;
                default:
                    $result = $ore." ore";
                    break;
            }
            return $result;
        }

        function periodicitaToString($val){
            $periodicita = "";
            if($val == 0){
                $periodicita = "Senza scadenza";
            } else if($val == 1){
                $periodicita = $val." mese";
            } else {
                $periodicita = $val." mesi";
            }
            return $periodicita;
        }

        function formatDate($date){
            return date_format($d,"d/m/Y");
        }

        function findAvailableName($path, $filename) {
            $res = "$path/$filename";
            if (!file_exists($res)) return $res;
            $fnameNoExt = pathinfo($filename,PATHINFO_FILENAME);
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
            $i = 1;
            while(file_exists("$path/$fnameNoExt ($i).$ext")) $i++;
            return "$path/$fnameNoExt ($i).$ext";
        }

        function hasReadPermission($area){
            global $user;
            if($user[$area] == "R" || $user[$area] == "W" || $user[$area] == "S"){
                return true;
            }
            return false;
        }
    
        function hasWritePermission($area){
            global $user;
            if($user[$area] == "W"){
                return true;
            }
            return false;
        }

        function getDisabledByPerm($area){
            global $user;
            if($user[$area] != "W"){
                return "disabled";
            }
            return "";
        }

    }

?>