<?php

$queries = array();
$params = array();

//placeholder to be replaced before call bitQuery
//$collection_id = "%collection_id%";
$collection_id = "0x06012c8cf97bead5deae237070f9587f8e7a266d";

$queries["collection_info"] = '
    query ($address: String!) {
        ethereum {
        address(address: {is: $address}) {
        smartContract {
            attributes {
            name
            type
            address {
                address
                annotation
            }
            value
            }
        }
        }
            transfers(
                currency: {is: "0x06012c8cf97bead5deae237070f9587f8e7a266d"}
                amount: {gt: 0}
            ){
                currency {
                symbol
            }
            count
            days: count(uniq: dates)
            sender_count: count(uniq: senders)
            receiver_count: count(uniq: receivers)
            min_date: minimum(of: date)
            max_date: maximum(of: date)
            }}
        }
    ';

$params["collection_info"] = [
    'address'=> "0x06012c8cf97bead5deae237070f9587f8e7a266d"
];

$queries["month-by-month-transfer"] = '
{
    ethereum {
      transfers(currency: {is: "'.$collection_id.'"}, height: {gt: 0}, amount: {gt: 0}) {
        date {
          date(format: "%Y-%m")
        }
        count
        amount
      }
    }
  }
';


$querys["month-by-month-transfer"] = '

';





?>