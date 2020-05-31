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
		
		if (!empty($user)) {
			session_start();
			$_SESSION['auth'] = true;
			$_SESSION['id'] = $user['worker_id'];
			$_SESSION['login'] = $user['login'];
			$_SESSION['status'] = $user['status'];
			$_SESSION['name'] = $user['firstname'] . " " . $user['lastname'];
			

		} else {
			echo "SQL вернула пустой результат";
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
        <title>Neyronikum CRM</title>
		<? include_once("templates/meta.html"); ?>
	</head>
	<body class="sidebar-mini skin-blue pace-done fixed" style="height: auto; min-height: 100%;">
		<div class="wrapper" style="height: auto; min-height: 100%;">	


			<? include_once('templates/header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content" style="min-height: 100%; background-color: #ececec;">
     <div class="row">
     <?
 		include_once('modules/new_sale.php');
    ?>
    	

    </div>  
    
    </section>
      <?include_once('modules/add_contragents.html');
      include_once('modules/blueprint_modal.html');?>
  </div>  
  <? include_once('templates/footer.html') ?>
		</div>
        <? include_once("templates/scripts.html"); ?>
	</body>

</html>
