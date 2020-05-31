<?php
	require_once('connect.php');
	require_once('bot.php');
	if ($_GET['new_task']){
		$query = "SELECT * FROM `tasks` left join `workers` on tasks.responsible = workers.worker_id where accepted = 0";
		$result = mysqli_query($link, $query);		
		while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$text = $user['task_name'];
			$date = date_create($user['date_end']);
			$date_formated = date_format($date, 'd.m.y');
			$priority = $user['priority'];
			$viber_id = $user['viber_id'];
			$sendingMessage = "Вам назначена задача: $text, на $date_formated, с приоритетом: $priority";
			$message['receiver'] = $viber_id;
			$message['type'] = "text";
			$message['text'] = $sendingMessage;
			$message['keyboard'] = [
				"Type" => "keyboard",
				"DefaultHeight" => true,
				"Buttons" => [
					[
						"ActionType" => "reply",
						"ActionBody" => "accept_destiny",
						"Text" => "Принять судьбу",
						"TextSize" => "regular"
					],
					
				]
			];
			file_put_contents("send.txt", json_encode($message));
			send($message);	
		};
	}
	if ($_GET['payment_delay']){
		$query = "SELECT * FROM `sales` LEFT JOIN `workers` on sales.worker_id = workers.worker_id LEFT JOIN `contragents` on sales.contragent_id = contragents.contragent_id  WHERE MONTH(`delay_date`) = MONTH(NOW()) AND YEAR(`delay_date`) = YEAR(NOW()) AND DAY(`delay_date`) = DAY(NOW()) AND `sales`.`realization` <> `sales`.`pay`";
		$result = mysqli_query($link, $query);
		while ($delay = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$viber_id = $delay['viber_id'];
			$sendingMessage = "${delay['name']} по сделке на сумму ${delay['realization']} до сих пор не заплатил!";
			$message['receiver'] = $viber_id;
			$message['type'] = "text";
			$message['text'] = $sendingMessage;
			$message['keyboard'] = [
				"Type" => "keyboard",
				"DefaultHeight" => true,
				"Buttons" => [
					[
						"ActionType" => "reply",
						"ActionBody" => "accept_destiny",
						"Text" => "Принять судьбу",
						"TextSize" => "regular"
					],
					
				]
			];
			file_put_contents("send.txt", json_encode($message));
			send($message);	
		}
	}

if ($_GET['conversation_control']){
    $query = "SELECT * FROM `conversations` c left join contragents c2 on c.contragent_id = c2.contragent_id left join workers w on c.worker_id = w.worker_id where month(now()) = month(c.conversation_date) and year(now()) = year(c.conversation_date) and `c`.`is_closed` <> 1";
    $result = mysqli_query($link, $query);
    $sendingMessage = "";
    while ($convers = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $sendingMessage = "Напоминание по компании ${convers['name']} - ${convers['reason']}";
        $viber_id = $convers['viber_id'];

        $message['receiver'] = $viber_id;
        $message['type'] = "text";
        $message['text'] = $sendingMessage;
        $message['keyboard'] = [
            "Type" => "keyboard",
            "DefaultHeight" => true,
            "Buttons" => [
                [
                    "ActionType" => "reply",
                    "ActionBody" => "accept_destiny",
                    "Text" => "Принять судьбу",
                    "TextSize" => "regular"
                ],

            ]
        ];
        file_put_contents("send.txt", file_get_contents("send.txt").json_encode($message).PHP_EOL);
        send($message);
    }
    echo $sendingMessage;

}



?>