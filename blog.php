<?php
require_once "block/config.php";//подключаю файл 

header('Content-type: text/html; charset=utf-8');
require_once ("block/blogs.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Блог</title><!--Заглавия можешь менять по твоему усматрению-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <!--Указывает на какмо 
кодировке сахранен файл -->
<?php include "block/js.php"; ?>

</head>
<?php 
//Проверяю есть ли в глобальном переменой такие элементы как email и password 
//Если их нет то ползователь просто так зашел или не вел одну из форм


		if (isset($_COOKIE['user'])){
		$u = 1;
		$email = $_COOKIE['user'];
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
			if(isset($_GET['option'])){
				$obgect = trim (strip_tags($_GET['option']));
			}
			else {
				$obgect = "myblog";
			}
			if (file_exists("block/".$obgect.".php")){
				include("block/".$obgect.".php");
				$obj = new $obgect;
				$obj->get_body();
			}
			else{
				exit("Вы задали не правильные пораметры");
			}
		}
		else {
			echo 'Вы не верно вели пароль или email либо вошли без входа на страничку.';
		}
	?>
</div>

</body>
</html>
