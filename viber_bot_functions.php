<?php
require_once('connect.php');
function send($message){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://chatapi.viber.com/pa/send_message",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => JSON_encode($message),
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/JSON",
            "X-Viber-Auth-Token: 49641b9631a7d853-ddaae5af403386c2-4d3a94bd05ade18e"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}
$buttons = array(
    "main_menu" => [
        "ActionType" => "reply",
        "ActionBody" => "main_menu",
        "Text" => "В главное меню",
        "TextSize" => "regular"
    ]
);

function viber($viber_id, $bot_text = "", $action_body){
    $message['receiver'] = $viber_id;
    $message['type'] = "text";
    $message['text'] = $bot_text;

    $message['keyboard'] = [
        "Type" => "keyboard",
        "DefaultHeight" => true,
        "Buttons" => [
            "main_menu" => [
                "ActionType" => "reply",
                "ActionBody" => "main_menu",
                "Text" => "В главное меню",
                "TextSize" => "regular"
            ],

        ]
    ];

    send($message);
    exit;
}


