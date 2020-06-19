<?php
require_once('connect.php');


if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
	if (!empty($_COOKIE['login']) and !empty($_COOKIE['key'])) {
		$login = $_COOKIE['login'];
		$key = $_COOKIE['key'];
		$query = "SELECT * FROM `workers` where login = '$login'";
		$result = mysqli_query($link, $query);		
		// $user = $result->fetch_assoc();
		$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if (!empty($user) && $key === $user['cookie']) {
			session_start();
			$_SESSION['auth'] = true;
			$_SESSION['id'] = $user['worker_id'];
			$_SESSION['login'] = $user['login'];
			$_SESSION['status'] = $user['status'];
			$_SESSION['name'] = $user['firstname'] . " " . $user['lastname'];
			

		} else {
			echo "Сессия устарела";
            header ("Location: login.php");
		}
	} else {
		header ("Location: login.php");
        exit();
	}
}
// mysqli_close($link);
?>
<html lang="ru">
	<head>
        <title>Работник</title>
		<? include_once("templates/meta.html"); ?>
	</head>
	<body class="sidebar-mini skin-blue pace-done fixed sidebar-collapse" style="height: auto; min-height: 100%;">
		<div class="wrapper" style="height: auto; min-height: 100%;">
            <? include_once('templates/header.php'); ?>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xs-12">

                <div class="box box-default" id="new_sale">
                    <div class="box-header with-border">
                        <h3 class="box-title">Добавление сотрудника</h3>


                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <form id="add_worker" role="form">
                            <div class="form-group">
                                <label for="contragent_to_convers">Контрагент</label>
                                <select name="contragent_to_convers" id="contragent_to_convers" class="form-control selectpicker"  data-live-search="true">

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea name="description" id="description" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="call_or_not" name="call_or_not">
                                <label for="call_or_not">Позвонить позже</label>
                            </div>
                            <div class="form-group">
                                <label for="day_to_call">дней до звонка</label>
                                <input type="number" id="day_to_call" name="day_to_call" class="form-control" disabled>
                            </div>
                            <button type="submit" class="btn btn-primary" >Добавить</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    
    </section>
  </div>  
  <? include_once('templates/footer.html') ?>
		</div>		
	</body>
	<? include_once("templates/scripts.html"); ?>
</html>
