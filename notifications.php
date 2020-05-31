<?php
function sendNotification($token_id){
    $url = 'https://fcm.googleapis.com/fcm/send';
    $YOUR_API_KEY = 'AAAAFYBbkZI:APA91bHGaGHOG9rU21tXOAHv9gbvpSwCpPKu7oSE78E1TOiqvoBoFaHvmVUdGW6yLxE1GtzsnmnIOFUtQ_gg5jaouecfB47BBVsfTfeJgieeFeI07gszaK3t6r_3aCQsCVqTJRb5zgx9';
    $YOUR_TOKEN_ID = $token_id ? $token_id : 'fV75wCzOOH0:APA91bENxh6D7pnmVNLstV-DI7u_y8zeH_YHcxJT-WfN6YtxTxUQflRovzx6Z4uecq4qH_elHlMonKdk8UZnJHWXVJFaMRxES9Ej76QT-UyNRidnEIxDSZKTT6gA12vXvfuILT5Xqnz4';
    $tokens = array();
    foreach($YOUR_TOKEN_ID as  $value){
        $tokens[] = $value;
    }
    $request_body = [
        'registration_ids' => $tokens,
        'notification' => [
            'title' => 'Проверка',
            'body' => sprintf('Начало в %s.', date('H:i')),
            'icon' => 'https://eralash.ru.rsz.io/sites/all/themes/eralash_v5/logo.png?width=192&height=192',
            'click_action' => 'https://shop.alt-oil.ru',
        ],
    ];
    $fields = json_encode($request_body);

    $request_headers = [
        'Content-Type: application/json',
        'Authorization: key=' . $YOUR_API_KEY,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

