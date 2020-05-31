<?php
/*******поступающая информация записывается в файл	***/
 file_put_contents("viber.txt",file_get_contents("php://input"));
$viber = file_get_contents("viber.txt");
$viber = JSON_decode($viber);

//******Соединение с базой данных
require_once('connect.php');
require ('viber_bot_functions.php');

if ($viber->event == "conversation_started"){
	$message['receiver'] = $viber->user->id;
	$message['type'] = "text";
	$message['text'] = "Чего желаете?";
	$message['keyboard'] = [
		"Type" => "keyboard",
		"DefaultHeight" => true,
		"Buttons" => [
			[
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
if ($viber->event == "message"){
						/*******инициализация   ***/
	$textMessage = $viber->message->text;
	$pregMessage =  preg_split("/[_]/", $textMessage);
	if ($textMessage == "contacts"){
		$worker = $viber->sender->id;
		$query = "SELECT * FROM workers where `viber_id` = '$worker';";
		$result = mysqli_query($link, $query);
		$workers = [];
		$row = $result->fetch_assoc();
		if($row){
			foreach ($row as $key => $value) {
				$workers[$key] = $value;
			}
		}
		$message['receiver'] = $viber->sender->id;
		$message['type'] = "text";
		$message['text'] = "Привет ${workers['firstname']}";
		$message['keyboard'] = [
			"Type" => "keyboard",
			"DefaultHeight" => true,
			"Buttons" => [
				[
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
	if ($textMessage == "working_hours"){

		
		$query = "SELECT * FROM workers;";
		$result = mysqli_query($link, $query);
		$workers = [];
		while($row = $result->fetch_assoc()){	
			if ($row[viber_id] == $viber->sender->id) continue;	
			$workers[] = array(	
				"ActionType" => "reply",
				"ActionBody" => "do_${row['firstname']}",
				"Text" => "${row['firstname']} ${row['secondname']}",
				"TextSize" => "regular"
				);	
		}
	    $workers[] = array(
						"ActionType" => "reply",
						"ActionBody" => "main_menu",
						"Text" => "В главное меню",
						"TextSize" => "regular"
					);
		$message['receiver'] = $viber->sender->id;
		$message['type'] = "text";
		$message['text'] = "для кого?";
		$message['keyboard'] = [
			"Type" => "keyboard",
			"DefaultHeight" => true,
			"Buttons" => $workers
		];
		send($message);
		file_put_contents("send", $message);
		exit;
	}
	if ($textMessage == "main_menu"){
		$message['receiver'] = $viber->sender->id;
	$message['type'] = "text";
	$message['text'] = "Чего изволите?";
	$message['keyboard'] = [
		"Type" => "keyboard",
		"DefaultHeight" => true,
		"Buttons" => [
			[
				"ActionType" => "reply",
				"ActionBody" => "no_pay",
				"Text" => "Неоплаченные отгрузки",
				"TextSize" => "regular"
			],
		]
	];
	send($message);
	file_put_contents("send.txt", json_encode($message));
	exit;
	}

	if ($textMessage == "accept_destiny"){
		$viber_id = $viber->sender->id;
		$query = "SELECT * FROM workers where viber_id = '$viber_id';";
		$result = mysqli_query($link, $query);
		$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$worker_id = $user['worker_id'];
		$query = "UPDATE `tasks` SET `accepted` = 1 where `responsible` = $worker_id";
		$result = mysqli_query($link, $query);
		$message['receiver'] = $viber->sender->id;
		$message['type'] = "text";
		$message['text'] = "Умный выбор";
		$message['keyboard'] = [
			"Type" => "keyboard",
			"DefaultHeight" => true,
			"Buttons" => [
				[
					"ActionType" => "reply",
					"ActionBody" => "no_pay",
					"Text" => "Неоплаченные отгрузки",
					"TextSize" => "regular"
				],
			]
		];
		send($message);
		file_put_contents("send.txt", json_encode($message));
		exit;
	}
	if ($textMessage == "no_pay"){
		$viber_id = $viber->sender->id;
		$query = "SELECT * FROM `sales` LEFT JOIN `workers` on `sales`.`worker_id` = `workers`.`worker_id` LEFT JOIN `contragents` on `contragents`.`contragent_id` = `sales`.`contragent_id` where `pay` <> `realization` and `viber_id` = '$viber_id';";
		$result = mysqli_query($link, $query);
		$answer = '';
		$count = 1;
		while ($contragent = mysqli_fetch_array($result, MYSQLI_ASSOC))
			$answer .= $count.')'.$contragent['name'].' '.$contragent['realization'].' ';
			$count += 1; 

		$message['receiver'] = $viber->sender->id;
		$message['type'] = "text";
		$message['text'] = $answer;
		$message['keyboard'] = [
			"Type" => "keyboard",
			"DefaultHeight" => true,
			"Buttons" => [
				[
					"ActionType" => "reply",
					"ActionBody" => "main_menu",
					"Text" => "В главное меню",
					"TextSize" => "regular"
				],
			]
		];
		send($message);
		file_put_contents("send.txt", json_encode($message));
		exit;
	}



	// if ($viber->message->text == )
}
if (!empty($_POST['worker'])){
	$worker_id = $_POST['worker'];
	$query = "SELECT * FROM workers where worker_id = '$worker_id';";
	$result = mysqli_query($link, $query);
	$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$viber_id = $user['viber_id'];
	$text = $_POST['text'];
	$date = date_create($_POST['date']);
	$date_formated = date_format($date, 'd.m.y');
	$priority = $_POST['priority'];
	$query = "INSERT INTO `tasks` (`task_name`, `date_end`, `responsible`) values ('$text', '$date_formated', $worker_id);";
	$result = mysqli_query($link, $query);
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
	exit;
}
?>