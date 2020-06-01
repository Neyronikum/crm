<?
if ($_SERVER["REQUEST_METHOD"] === "POST"){
	require_once('connect.php');
	require_once ('notifications.php');
	session_start();
	if ($_POST['sales_table']){		
		$is_current_month = (int)$_POST['current_month'];
		$all_sales = (int)$_POST['all_sales'];
		$worker_id = $_SESSION['id'] ? $_SESSION['id'] : 1;
		$status = $_SESSION['status'] ? $_SESSION['status'] : 1;
		if (($is_current_month === 1) && ($all_sales === 1)){
			$query = "SELECT * FROM `sales` s LEFT JOIN `workers` w on s.worker_id = w.worker_id LEFT JOIN `contragents` c on s.contragent_id = c.contragent_id  WHERE  MONTH(`sale_date`) = MONTH(NOW()) AND YEAR(`sale_date`) = YEAR(NOW())  ORDER BY sale_id DESC";
		} else
		if (($is_current_month === 0) && ($all_sales === 1)){
            $query = "SELECT * FROM `sales` s LEFT JOIN `workers` w on s.worker_id = w.worker_id LEFT JOIN `contragents` c on s.contragent_id = c.contragent_id  ORDER BY sale_id DESC";
        } else
		if (($is_current_month === 1) && ($all_sales === 0)){
            $query = "SELECT * FROM `sales` s LEFT JOIN `workers` w on s.worker_id = w.worker_id LEFT JOIN `contragents` c on s.contragent_id = c.contragent_id  WHERE w.worker_id = ${worker_id} and MONTH(`sale_date`) = MONTH(NOW()) AND YEAR(`sale_date`) = YEAR(NOW())  ORDER BY sale_id DESC";
        }
		else {
			$query = "SELECT * FROM `sales` s LEFT JOIN `workers` w on s.worker_id = w.worker_id LEFT JOIN `contragents` c on s.contragent_id = c.contragent_id  WHERE w.worker_id = ${worker_id}  ORDER BY sale_id DESC";
		}
		//echo (bool)$_POST['current_month'];
        $is_current_month_sql = $is_current_month ? "MONTH(`sale_date`) = MONTH(NOW()) AND YEAR(`sale_date`) = YEAR(NOW())" : "1";
        $worker_id_sql = $worker_id ? "w.worker_id = ${worker_id}" : "1";
        $query = $query = "SELECT * FROM `sales` s LEFT JOIN `workers` w on s.worker_id = w.worker_id LEFT JOIN `contragents` c on s.contragent_id = c.contragent_id  WHERE ".$is_current_month_sql." AND ".$worker_id_sql." ORDER BY sale_id DESC";

		$result = mysqli_query($link, $query);	

		$sales_table = array();
//		$realization_sum;
//		$purchase_sum;
//		$pay_sum;
//		$logisticInside_sum;
//		$reward_sum;
//		$profitability_sum;
		while ($sale = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			// $count;
//		$realization_sum += $sale['realization'];
//		$purchase_sum += $sale['purchase'];
//		$pay_sum += $sale['pay'];
//		$logisticInside_sum += $sale['logisticInside'];
//		$reward_sum += $sale['reward'];
//		$profitability_sum += $sale['profitability'];
//		$date = date_create($sale['sale_date']);
//		$sale['sale_date'] = date_format($date, 'd.m.y');
		$sales_table[] = $sale;
		}
		//echo $query;
		//echo json_encode([$is_current_month, $all_sales]);
		echo json_encode($sales_table, JSON_NUMERIC_CHECK);
	}

	if ($_POST['sale_edit']) {
		$new_pay = $_POST['new_pay'];
		$sale_id = $_POST['sale_id'];
		$sale_edit = date("Y-m-d H:i:s");
		$query = "UPDATE `sales` SET `pay` = ${new_pay}, `change_date` = '${sale_edit}' WHERE `sale_id`=${sale_id}";
		mysqli_query($link, $query);

//        $worker_id = $_SESSION['id'];
//        $query = "SELECT `notification_tokens` FROM `workers` WHERE `worker_id` = ${worker_id}";
//        $result = mysqli_query($link, $query);
//        $notifications = mysqli_fetch_array($result, MYSQLI_ASSOC);
//        $notifications_array = explode(',', $notifications['notification_tokens']);
//        $answer = sendNotification($notifications_array);
        echo $query;
        exit();
		//echo "Оплата по id = ${sale_id} изменена на ${new_pay}.";
	}

	if ($_POST['sale_delete']) {
		$sale_id = $_POST['sale_id'];
		$query = "DELETE FROM `sales` WHERE `sale_id` = ${sale_id}";
		mysqli_query($link, $query);
	}

	if($_POST['add_contragent']) {
		$worker_id = $_SESSION['id'];
		$contragent =[];
		if($_POST['human']){
            $contragent['name'] = $_POST['human'];
            $contragent['is_ogrn'] = 0;
		}
		else {
		    $contragent['is_ogrn'] = 1;
            if($_POST['name_short']){
              $contragent['name'] = mysqli_real_escape_string($link, $_POST['name_short']);
            }
            if($_POST['inn_kpp']){
              $contragent['inn_kpp'] = $_POST['inn_kpp'];
            }
            if($_POST['address']){
              $contragent['address'] = $_POST['address'];
            }
		}

		if($_POST['emails']){
		    $contragent['emails'] = $_POST['emails'];
		}
		if($_POST['telephones']){
		    $contragent['telephones'] = $_POST['telephones'];
		}
		if($_POST['about']){
		    $contragent['about'] = $_POST['about'];
		}
		if ($_POST['contragent_status']){
            $condition = $_POST['contragent_status'];
        }
		$query = "INSERT INTO `contragents` (`name`, `inn_kpp`, `address`, `emails`, `telephones`, `about`, `is_ogrn`, `add_by_worker`, `condition_id`) VALUES('${contragent['name']}', '${contragent['inn_kpp']}', '${contragent['address']}', '${contragent['emails']}', '${contragent['telephones']}', '${contragent['about']}', ${contragent['is_ogrn']}, ${worker_id}, ${condition});";


		mysqli_query($link, $query);
		echo json_encode($query);

	}

	if($_POST['add_sale']){
		$contragent_id = $_POST['contragent_id'];
		$realization = $_POST['realization'];
		$purchase = $_POST['purchase'];
		$NDS = $_POST['NDS'];
		$pay = $_POST['pay'];
		$weight = $_POST['weight'];
		$commission = $_POST['commission'];
		$profit = $_POST['profit'];
		$NDSOut = $_POST['NDSOut'];
		$NDSIn = $_POST['NDSIn'];
		$NDSToPay = $_POST['NDSToPay'];
		$logisticOutside = $_POST['logisticOutside'];
		$logisticInside = $_POST['logisticInside'];
		$taxBase = $_POST['taxBase'];
		$taxProfit = $_POST['taxProfit'];
		$cleanProfit = $_POST['cleanProfit'];
		$reward = $_POST['reward'];
		$profitability = $_POST['profitability'];
		$sale_date = $_POST['sale_date'];
		$change_date = date("Y-m-d H:i:s");
		$worker_id = $_SESSION['id'];
		$payment_delay = $_POST['payment_delay'];
		$delay_plus_one = $payment_delay + 1;
		$delay_date = date("Y-m-d H:i:s", strtotime($sale_date. " + ${delay_plus_one} day"));
        $distance = $_POST['distance']!=''?$_POST['distance']:'null';
        $transport_id = $_POST['car']!=''?$_POST['car']:'null';
        $comment = $_POST['comment'];


		$query = "INSERT INTO `sales` (`contragent_id`, `realization`, `purchase`, `NDS`, `pay`, `weight`, `commission`, `profit`, `NDSOut`, `NDSIn`, `NDSToPay`, `logisticOutside`, `logisticInside`, `taxBase`, `taxProfit`, `cleanProfit`, `reward`, `profitability`, `sale_date`, `change_date`, `worker_id`, `payment_delay`, `delay_date`, `distance`, `transport_id`, `comment`) VALUES ('${contragent_id}', '${realization}', '${purchase}', '${NDS}', '${pay}', '${weight}', '${commission}', '${profit}', '${NDSOut}', '${NDSIn}', '${NDSToPay}', '${logisticOutside}', '${logisticInside}', '${taxBase}', '${taxProfit}', '${cleanProfit}', '${reward}', '${profitability}', '${sale_date}', '${change_date}', ${worker_id}, '${payment_delay}', '${delay_date}', ${distance}, ${transport_id}, '${comment}')";
		mysqli_query($link, $query);
		echo $query;
		exit();
	}

	if($_POST['get_contragents']){
		$query = "SELECT `worker_id`, `name`, `contragent_id`, `firstname` FROM `contragents` LEFT JOIN `workers` on `workers`.`worker_id` = `contragents`.`add_by_worker` WHERE `add_by_worker` = `worker_id`";
		$result = mysqli_query($link, $query);
		$contragents = array();
		while ($contragent = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$contragents[] = $contragent;
		}
		echo json_encode($contragents);
		exit();
	}

	if ($_POST['add_conversation']){
        $worker_id = $_SESSION['id'] ? $_SESSION['id'] : 1;
        $contragent_id = $_POST['contragent_to_convers'];
        $description = $_POST['description'];
        $conversation_date = date("Y-m-d H:i:s");
        $date_control = $_POST['$date_control'] ? $_POST['$date_control'] : null;
        $date = date("Y-m-d");
        $reason = $_POST['conversation_reason'];
        $query = "INSERT INTO `conversations` (`worker_id`, `date`, `contragent_id`, `description`, `conversation_date`, `reason`) VALUES (${worker_id}, '${date}', ${contragent_id}, '${description}', '${conversation_date}', '${reason}');";
        mysqli_query($link, $query);
        $query = "UPDATE `contragents` SET `recall_date` = '${date_control}' WHERE `contragent_id` = ${contragent_id}";
        mysqli_query($link, $query);
        echo json_encode([$worker_id,$contragent_id,$description,$conversation_date,$date_control]);
    }

	if ($_POST['get_conditions']){
	    $query = "SELECT * FROM `conditions`";
        $result = mysqli_query($link, $query);
        $conditions = array();
	    while ($condition = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	        $conditions[] = $condition;
        }
        echo json_encode($conditions, JSON_NUMERIC_CHECK);
    }

	if ($_POST['get_contragents_table']){
	    $query = "SELECT * FROM `contragents` LEFT JOIN `conditions` c on contragents.condition_id = c.condition_id left join workers w on contragents.add_by_worker = w.worker_id";
	    $result = mysqli_query($link, $query);
	    $contragents = array();
	    while ($contragent = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	        $contragents[] = $contragent;

        }
        echo json_encode($contragents, JSON_NUMERIC_CHECK);
    }

	if ($_POST['add_notification_token']){
        $worker_id = $_SESSION['id'];
        $token = $_POST['token'];
        $query = "SELECT `notification_tokens` FROM `workers` WHERE `worker_id` = ${worker_id}";
        $result = mysqli_query($link, $query);
        $notifications = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $notifications_array = explode(',', $notifications['notification_tokens']);
        if (!in_array($token, $notifications_array)){
            $query = "UPDATE `workers` SET `notification_tokens` = CONCAT_WS(',', '${token}', `notification_tokens`) WHERE worker_id = ${worker_id}";
            mysqli_query($link, $query);
            echo $query;
        } else {
            echo "token пользователя уже добавлен в базу данных.";
        }
        //echo json_encode($notifications_array);

    }
	if ($_POST['get_worker_profit']){
	    $worker_id = $_POST['worker_id'];
	    $month = date('Y-m-d', strtotime($_POST['month']));

	    $query = "SELECT * FROM `sales` s LEFT JOIN contragents c on s.contragent_id = c.contragent_id WHERE month(`sale_date`) = month('${month}') and year(`sale_date`) = year('${month}') and worker_id = ${worker_id}";
        $sales = array();
	    $result = mysqli_query($link, $query);
	    while ($sale = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $sales[] = $sale;
        };
	    $itog = array();
        foreach ($sales as $key => $value){
            $itog['realization_sum'] += $value['realization'];
            $itog['pay_sum'] += $value['pay'];
            $itog['reward'] += $value['reward'];
            $itog['profit_sum'] += $value['profit'];
        }
        $itog['debit'] = $itog['realization_sum'] - $itog['pay_sum'];
        $itog['demotivation'] = $itog['debit'] * 0.002;
        $query = "SELECT * FROM `salary` s left join workers w on s.worker_id = w.worker_id where s.worker_id = ${worker_id} and month(`salary_date`) = month('${month}') and year(`salary_date`) = year('${month}')";
        $result = mysqli_query($link, $query);
        $salary = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (mysqli_num_rows($result) != 0){
            $itog['new_clients_plan'] = $salary['new_clients_plan'];
            $itog['new_clients'] = $salary['new_clients'];
            if (($salary['new_clients'] - $salary['new_clients_plan']) >= 0) {
                $itog['clients_plan_motivation'] = ($salary['new_clients'] - $salary['new_clients_plan']) * $itog['reward'] * 0.01;
            } else {
                $itog['clients_plan_motivation'] = 0;
            }
            if ($itog['realization_sum'] > $itog['realization_plan']){
                $itog['overplan_motivation'] = $itog['realization_sum'] * 0.01;
            } else {
                $itog['overplan_motivation'] = 0;
            }
            $itog['realization_plan'] = $salary['realization_plan'];
            $itog['salary_base'] = $salary['salary_base'] == "null"? 0 : $salary['salary_base'];
            $itog['FOT_tax'] = $salary['salary_base'] * 0.4;
            $sub_query = "SELECT * FROM `bonuses` where worker_id = ${worker_id} and month(`for_month`) = month('${month}') and year(`for_month`) = year('${month}')";
            $sub_result = mysqli_query($link, $sub_query);
            if (mysqli_num_rows($sub_result) != 0){
                while ($bonus = mysqli_fetch_array($sub_result, MYSQLI_ASSOC)){
                    $itog['bonuses'] += $bonus['bonus_count'];
                }
            } else {
                $itog['bonuses'] = 0;
            }
            $itog['salary_profit'] = $itog['reward'] + $itog['clients_plan_motivation'] + $itog['overplan_motivation'] + $itog['salary_base'] - $itog['demotivation'] + $itog['bonuses'];

            $query = "SELECT * FROM `worker_payroll` WHERE worker_id = ${worker_id} and month(`payroll_for_month`) = month('${month}') and year(`payroll_for_month`) = year('${month}')";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) != 0){
                while ($payroll = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $itog['salary_payroll'] += $payroll['amount'];
                }
                $itog['spending'] = $itog['salary_payroll'] + $itog['FOT_tax'];

            } else {
                $itog['spending'] = $itog['FOT_tax'];
            }
            $itog['salary_payable'] = $itog['salary_profit'] - $itog['spending'];
            $itog['fin_result'] = $itog['realization_sum'] - $itog['salary_profit'] - $itog['FOT_tax'];
            $itog['gross_margin'] = $itog['realization_sum'] <> 0 ? $itog['fin_result'] / $itog['realization_sum']:0;
            $itog['return_of_investment'] = $itog['pay_sum'] <> 0 ? ($itog['profit_sum'] + $itog['realization_sum'] - $itog['pay_sum']) / $itog['pay_sum']: 0;
            //$itog[''] ='';

        } else {
            $itog['ready'] = 'false';
            $previous_month = date("Y-m-d", strtotime($month . " - 1 month")); // Необходимо для автоматического заполнения плановых величин
            $query = "SELECT * FROM `salary` s left join workers w on s.worker_id = w.worker_id where s.worker_id = ${worker_id} and month(`salary_date`) = month('${previous_month}') and year(`salary_date`) = year('${previous_month}')";
            $result = mysqli_query($link, $query);
            $salary = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $itog['new_clients_plan'] = $salary['new_clients_plan'];
            $itog['realization_plan'] = $salary['realization_plan'];
            $itog['salary_base'] = $salary['salary_base'];
        }

        array_walk($itog, function(&$val){
            $val = number_format($val, 2, ".", " ");
        });

        echo json_encode($itog, JSON_NUMERIC_CHECK);
        exit();
    }

	if ($_POST['set_month_standard']){
	    $worker_id = $_POST['worker_id'];
	    $new_clients = $_POST['new_clients'];
	    $realization_plan = $_POST['realization_plan'];
	    $salary_base = $_POST['salary_base'];
        $salary_date = date('Y-m-d', strtotime($_POST['month']));
	    $month_closed = false;//$_POST['month_closed'];
	    $edit_date = date('Y-m-d');
        $new_clients_plan = $_POST['new_clients_plan'];
	    $query = "SELECT * FROM `salary` where `worker_id` = ${worker_id} and month(`salary_date`) = month('${salary_date}') and year(`salary_date`) = year('${salary_date}')";
	    $result = mysqli_query($link, $query);
	    if (mysqli_num_rows($result) == 0){
            $query = "insert into `salary` (`salary_date`, `worker_id`, `new_clients`, `new_clients_plan`, `realization_plan`, `salary_base`, `edit_date`)  values ('${salary_date}', '${worker_id}', '${new_clients}', '${new_clients_plan}', '${realization_plan}', '${salary_base}', '${edit_date}')";
        } else {
            $query = "UPDATE `salary` SET `new_clients` = '${new_clients}', `new_clients_plan` = '${new_clients_plan}', `realization_plan` = '${realization_plan}', `salary_base` = '${salary_base}', `edit_date` = '${edit_date}' where worker_id = ${worker_id} and month(`salary_date`) = month('${salary_date}') and year(`salary_date`) = year('${salary_date}')";
        }
        mysqli_query($link, $query);
	    echo $query;
        exit();
    }

	if ($_POST['get_worker_bonus']){
        $worker_id = $_POST['worker_id'];
        $month = date('Y-m-d', strtotime($_POST['month']));

        $query = "SELECT * FROM `bonuses` where worker_id = ${worker_id} and month(`for_month`) = month('${month}') and year(`for_month`) = year('${month}')";
        $result = mysqli_query($link, $query);
        $bonuses = array();
        while ($bonus = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $bonuses[] = $bonus;
        }
        echo json_encode($bonuses, JSON_NUMERIC_CHECK) ;
    }

	if ($_POST['add_new_bonus']) {
        $worker_id = $_POST['worker_id'];
        $month = date('Y-m-d', strtotime($_POST['month']));
        $bonus_description = $_POST['reason'];
        $bonus_count = $_POST['bonus_count'];
        $bonus_date = date('Y-m-d');
        $query = "insert into `bonuses` (for_month, bonus_description, bonus_count, worker_id, bonus_date) values ('${month}', '${bonus_description}', ${bonus_count}, ${worker_id}, '${bonus_date}')";
        mysqli_query($link, $query);
        echo $query;
    }

	if ($_POST['get_worker_payroll']) {
        $worker_id = $_POST['worker_id'];
        $month = date('Y-m-d', strtotime($_POST['month']));

        $query = "SELECT * FROM `worker_payroll` where worker_id = ${worker_id} and month(`payroll_for_month`) = month('${month}') and year(`payroll_for_month`) = year('${month}')";
        $result = mysqli_query($link, $query);
        $payrolls = array();
        while ($payroll = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $payrolls[] = $payroll;
        }
        echo json_encode($payrolls, JSON_NUMERIC_CHECK) ;
    }

    if ($_POST['add_new_payroll']) {
        $worker_id = $_POST['worker_id'];
        $month = date('Y-m-d', strtotime($_POST['month']));
        $reason = $_POST['reason'];
        $payroll_count = $_POST['payroll_count'];
        $payroll_date = date('Y-m-d');
        $query = "insert into `worker_payroll` (payroll_for_month, description, amount, worker_id, payroll_date) values ('${month}', '${reason}', ${payroll_count}, ${worker_id}, '${payroll_date}')";
        mysqli_query($link, $query);
        echo $query;
    }

    if ($_POST['get_products']){
        $query = "SELECT * from products ";
        $result = mysqli_query($link, $query);
        $supliers = array();
        while ($suplier = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $supliers[] = $suplier;
        }
        echo json_encode($supliers, JSON_NUMERIC_CHECK);
    }

    if ($_POST['get_cars']){
        $query = "SELECT * FROM `transport`";
        $result = mysqli_query($link, $query);
        $cars = array();
        while ($car = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $cars[] = $car;
        }
        echo json_encode($cars, JSON_NUMERIC_CHECK);
    }

    if ($_POST['get_blueprints_table']){
        $worker_id = $_SESSION['id'];
        $query = "SELECT * FROM `conversations` left join contragents c on conversations.contragent_id = c.contragent_id left join workers w on c.add_by_worker = w.worker_id where conversations.worker_id = ${worker_id} order by conversation_id desc";
        $result = mysqli_query($link, $query);
        $blueprints = array();
        while ($blueprint = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $blueprints[] = $blueprint;
        }
        echo json_encode($blueprints, JSON_NUMERIC_CHECK);
    }

}
?>