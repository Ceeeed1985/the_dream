<?php

header('Content-Type: application/json');

$url = "https://currency-converter5.p.rapidapi.com/currency/convert?format=json&from=EUR&amount=1&language=fr";

$headers = [
    "X-RapidAPI-Host: currency-converter5.p.rapidapi.com",
    "X-RapidAPI-Key: 6435e53cd2mshdca89a6d4ef9b2ap120334jsn2fda24fc95bf"
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$data = curl_exec($ch);

curl_close($ch);

$result = json_decode($data, true);

echo json_encode($result);


?>
