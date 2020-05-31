<?php
if (isset($_POST['login']) && !empty($_POST['login'])) {
	require_once('connect.php');
	
	$login = $_POST['login'];
	$password = md5($_POST['password']);
	$query = "SELECT * FROM `workers` WHERE login = \"${login}\";";
	$result = mysqli_query($link, $query);		
	$user = $result->fetch_assoc();
	if (!empty($user)) {
		if ($user['password'] == $password) {
			session_start();
			$_SESSION['auth'] = true;
			$_SESSION['id'] = $user['worker_id'];
			$_SESSION['login'] = $user['login'];
			$_SESSION['status'] = $user['status'];
			$_SESSION['name'] = $user['firstname'] . " " . $user['lastname'];
			$key = md5($password.time());
			setcookie('login', $user['login'], time()+60*60*24*30); 
			setcookie('key', $key, time()+60*60*24*30);

			$query = "UPDATE `workers` SET cookie=\"$key\" WHERE login=\"${login}\";";
			mysqli_query($link, $query);
			header ("Location: index.php");
    		exit();
		}
		else {
			echo "<h1>Не правильный password</h1>";
		}
	}
	else {
		echo "<h1>Нет результатов в базе</h1>";
	}
	mysqli_close($link);
}

?>

<html>
	<head>
		<? include_once("templates/meta.html"); ?>
	</head>


	<body class="hold-transition login-page">
		<div class="login-box">
		  <div class="login-logo">
		    <a ><b>Альтер</b>натива</a>
		  </div>
		  <!-- /.login-logo -->
		  <div class="login-box-body">
		    <p class="login-box-msg">Авторизуйтесь, чтобы войти</p>

		    <form action="" method="post" class="form-element">
		      <div class="form-group has-feedback">
		        <input name="login" type="login" class="form-control" placeholder="Логин">
		        <span class="ion ion-email form-control-feedback"></span>
		      </div>
		      <div class="form-group has-feedback">
		        <input name="password" type="password" class="form-control" placeholder="Пароль">
		        <span class="ion ion-locked form-control-feedback"></span>
		      </div>
		      <div class="row">
		        <div class="col-6">
		          <div class="checkbox">
		            <input type="checkbox" id="basic_checkbox_1" >
					<label for="basic_checkbox_1">Запомнить</label>
		          </div>
		        </div>
		        <!-- /.col -->
		        <div class="col-6">
		         <div class="fog-pwd">
		          	<a href="javascript:void(0)"><i class="ion ion-locked"></i>Напомнить?</a><br>
		          </div>
		        </div>
		        <!-- /.col -->
		        <div class="col-12 text-center">
		          <button type="submit" class="btn btn-block btn-flat margin-top-10 btn-primary">Войти</button>
		        </div>
		        <!-- /.col -->
		      </div>
		    </form>

		    <div class="social-auth-links text-center">
		      
		    </div>
		    <!-- /.social-auth-links -->

		    <div class="margin-top-30 text-center">
		    	<p>Нет аккаунта? <a href="register.html" class="text-info m-l-5">Звоните 300-40-55</a></p>
		    </div>

		  </div>
		  <!-- /.login-box-body -->
		</div>
<!-- /.login-box -->

		
		<? include_once("templates/scripts.html"); ?>
	</body>
</html>

