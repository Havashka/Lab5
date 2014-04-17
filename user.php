<?php
//вставляю файл который отвечает за подключение к базу данных

include "block/config.php";

//My change is there
include "blog.php";

	session_start();

		$db = mysql_connect(HOST,USER,PASS);
	
		
		mysql_select_db(DB,$db);
		

		mysql_query("SET NAMES 'UTF8'");

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Блог</title><!--Заглавия можешь менять по твоему усматрению-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!--Указывает на какмо 
кодировке сахранен файл -->
<?php include "block/js.php"; 
//вставляю файл который подключает javascript?>
</head>
<?php 
//Проверяю есть ли в глобальном переменой такие элементы как email и password 
//Если их нет то ползователь просто так зашел или не вел одну из форм


	if (isset($_COOKIE['user'])&&isset($_COOKIE['id_user'])){
		$u = 1;
		$id= $_COOKIE['id_user'];
		$email = $_COOKIE['user'];
		setcookie('id_user',$id,time()+3600);
		setcookie('user',$email,time()+3600);
	}
if (isset ($_POST['email']) && isset($_POST['password'])){
	$email = $_POST['email'];
	$password = $_POST['password'];
	$query = "SELECT password,id FROM users WHERE email='$email'";
	$result = mysql_query($query,$db);
	$row = mysql_fetch_array($result);
	if ($password == $row['password']){
		$u = 1;
		$id = $row['id'];
		setcookie('id_user',$id,time()+3600);
		setcookie('user',$email,time()+3600);
	}
}
?>
<body>
<?php include ("block/menu.php"); ?>
<div id="button_doun"></div>
<div id="button_up"></div>
<div id="conteiner">
	
	<?php
		if ($u==1){
			if (isset($_COOKIE['user'])){
				$email = $_COOKIE['user'];
				
			}
			$query = "SELECT user, info FROM users WHERE email='$email'";
	$result = mysql_query($query,$db);
	$row = mysql_fetch_array($result);
		
			echo "<p>Все хорошо вы на правильном месте!</p>";
		}
		else {
			echo 'Вы не верно вели пароль или email';
		}
	?>
</div>

</body>
</html>
