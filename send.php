<?php

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

require 'vendor\autoload.php';

$credential = new ServiceAccountCredentials(
    "https://www.googleapis.com/auth/firebase.messaging",
    json_decode(file_get_contents("pvKey.json"), true)
);

$token = $credential->fetchAuthToken(HttpHandlerFactory::build());

$ch = curl_init("https://fcm.googleapis.com/v1/projects/belajar-real-time/messages:send");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer '.$token['access_token']
]);

$deviceTokens = [
    "fQxZLpm3QuyKt6s6XEgSFr:APA91bFokP_lAE25IUuK8OTuXe0PSzWbNNdAyFAVPrtGQ6WEPWGKj3cU5SXYt5tBsHnxDuo8fJbhTqExuXIOgbw-PizHHDZHBHQnvcs_qjjaX6xvLaLEtg9yI6iOjhzIGtGL3oFLt3PT",
    //"d3HyBNn3Tf6F9Z6v2jJYNZ:APA91bHN6qm64nxBKtg5xp_ZPKrol649kImisNJ2_idCG0BKXtPPstw4mWnTX5S__0wgrwU8wP6IqI9aOjDqHKwe-iYLHHgk12BebV_afI6hN_VJUY86Apef2KkW0jJ0mdXV0bTEYuKB"
];

// curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
foreach ($deviceTokens as $token) {
    $payload = json_encode([
        "message" => [
            "token" => $token,
            "notification" => [
                "body" => "Hujan deras terjadi di Jl. Gaperta Ujung, 10 menit lagi akan banjir nih",
                "title" => "Flodecs"
            ],
            "data" => [
                "Status" => "Banjir",
                "Ketinggian" => "10 cm",
                "Intensitas" => "7 mm/jam",
                "Debit" => "40 mm^3/menit"
            ],
            "android" => [
                "notification" => [
                  "icon" => "baseline_info_24"
                ]
            ],
            
        ]
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    
    $response = curl_exec($ch);
    
    echo $response;
}

curl_close($ch);
