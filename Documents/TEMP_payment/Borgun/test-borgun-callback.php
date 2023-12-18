<?php

$url = 'http://webshop.hlbs.local/payment-borgun-callback';

#-------------------------------------------------------------------------------

//$data = array(
//    'orderid' => '22800',
//    'orderhash' => 'c19f3c608ebf916469a937ae91e5b9905fdd2827b11569026174e548794e42f4',
//    'authorizationcode' => '123456',
//    'creditcardnumber' => '474152******0003',
//    'customField' => '',
//    'ticket' => '',
//    'buyername' => 'Foo Bar',
//    'buyeraddress' => '',
//    'buyerzip' => '',
//    'buyercity' => '',
//    'buyercountry' => '',
//    'buyerphone' => '',
//    'buyeremail' => '',
//    'buyerreferral' => '',
//    'buyercomment' => '',
//    'merchantid' => '9275444',
//    'amount' => '223.1',
//    //'amount' => '100',
//    'currency' => 'EUR',
//    'reference' => '',
//    'status' => 'OK',
//    //'step' => 'Confirmation',
//    'step' => 'Payment',
//);
$data = array(
    'orderid' => '22815',
    'orderhash' => 'a182bdd1f8e355dad6fc6ef7546c3c6ede6ec99277710e7d0c90f46d64052f42',
    'authorizationcode' => '123456',
    'creditcardnumber' => '474152******0003',
    'customField' => '',
    'ticket' => '',
    'buyername' => 'Foo Bar',
    'buyeraddress' => '',
    'buyerzip' => '',
    'buyercity' => '',
    'buyercountry' => '',
    'buyerphone' => '',
    'buyeremail' => '',
    'buyerreferral' => '',
    'buyercomment' => '',
    'merchantid' => '9275444',
    'amount' => '14.23',
    //'amount' => '100',
    'currency' => 'EUR',
    'reference' => '',
    'status' => 'OK',
    //'step' => 'Confirmation',
    'step' => 'Payment',
);

#-------------------------------------------------------------------------------

#-------------------------------------------------------------------------------


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
var_dump($response);




