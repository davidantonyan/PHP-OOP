<?php

error_reporting(E_ALL ^ E_NOTICE);

$url = "http://www.nhl.com";

function fetchRawData($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $data = curl_exec($ch);

    if(!$data){
        echo "<br>cURL error:</br>\n";
        echo "#". curl_errno($ch)."<br>\n";
        echo curl_error($ch)."<br>\n";
        echo "Detailed information:";
        var_dump(curl_getinfo($ch));
        die();
    }

    curl_close($ch);

    return $data;

}

$data = fetchRawData($url);

print_r($data);

