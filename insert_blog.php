<?php
require_once "block/config.php";//подключаю файл
session_start(); 
$db = mysql_connect(HOST,USER,PASS);
	
		
		mysql_select_db(DB,$db);
		

		mysql_query("SET NAMES 'UTF8'");
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



<script>
	$(document).ready(function(){
		$("#date").datepicker({"dateFormat":"yy-mm-dd"});	
	});
</script>
<script>
	$(document).ready(function(){
		$("#submit").click(function(){
			var title = $("#titles").val();
			var   description =  $("#description").val(); 
			var   date =  $("#date").val();
			var   text =  $("#texts").val();
			var   img = $("#img").val();
			var id = $("#id_user").val();
			$.ajax({
				type:'POST',
				url:'block/insert_blog.php',
				data:'images='+img+'&title='+title+'&desc='+description+'&date='+date+'&text='+text+'&id='+id,
				success:function(html){
					$("#info").html(html);
				}
			});
		});	
	});
</script>

<script src="ckeditor/ckeditor.js"></script>

</head>
<?php 
//Проверяю есть ли в глобальном переменой такие элементы как email и password 
//Если их нет то ползователь просто так зашел или не вел одну из форм


		if (isset($_COOKIE['user'])){
		$u = 1;
		$email = $_COOKIE['user'];
		setcookie('user',$email,time()+3600);
	}


?>
<body>
<?php include ("block/menu.php"); ?>
<div id="button_doun"></div>
<div id="button_up"></div>
<div id="conteiner">
	<div id='navig_menu'>
					<p><a href='blog.php?option=myblog'>Мои посты</a></p>
					<p><a href="settings.php">Редатировать посты</a></p>
				</div>
				
	<?php
		if ($u==1){
			@$id= $_GET['id'];
				$query = "SELECT user FROM users WHERE id='$id'";
				$result = mysql_query($query,$db);
				$row= mysql_fetch_array($result);
				$user = $row['user'];
				$user = trim($user);
				@$file = $_SESSION['file'];
				echo '<div id="conect">
				Сночало загрузите картинку. Формат картинки должен быть jpg либо png. Размер картинки не больше 1мб.
				<form action="insert_blog.php?id='.$id.'" method="post" enctype="multipart/form-data">
				<input type="file" name="images" id="images">
				
				<input type="submit" name="submit" value="Загрузить" id="image_load"/>
				
				</form>
				<form action="insert_blog.php?id='.$id.'" method="post" id="f_edit" >
					<input type="text" name="title" value="" id="titles" placeholder="Тема"/><br>
					<textarea name="description"  id="description" placeholder="Короткое описание"></textarea><br>
					
					Не менять формат датты<br>
					<input type="text" name="date" value="" id="date"/><br>
					<textarea name="text" id="texts" ></textarea><br>
					<script>
					CKEDITOR.replace("texts");
					</script>
					<input type="hidden" value="'.$file.'" name="img"  id="img">
					<input type="hidden" name="id" value="'.$id.'" id="id_user">
					<input type="submit" name="submit" value="Создать" id="submit"/>
				</form>
				
				
				<div id="info"></div>
				</div>';
				@$url = $_SESSION['url_img'];
				@$okey = $_SESSION['okey'];
				@$info = $_SESSION['info'];
				echo '<img src="'.$url.'" alt="" width="200" height="200"><br>';
				echo $info;
				unset($_SESSION['file']);
				unset($_SESSION['url_img']);
				unset($_SESSION['okey']);
				unset($_SESSION['info']);
			@$tempFile = $_FILES['images']['tmp_name'];
			@$file = $_FILES['images']['name'];
			@$url = "image/blog/".$user."/".$file;
			@$img_type=$_FILES['images']['type'];
			if($_FILES['images']['size']<1*1024*1024 || $okey==1){
				if($img_type=='image/png'||$img_type=='image/jpeg'||$img_type =='image/jpg'||$img_type=='image/pjpeg' ||$okey==1){
					if (move_uploaded_file($tempFile,$url)){
						$okey = 1;
						$_SESSION['okey']=$okey;
						$_SESSION['info']= "Загрузка картинки закончен удачно! Заполняйте форму и нажмите Создать<br>";
						//echo '<img src="'.$url.'" alt="" width="200" height="200">';
						$_SESSION['file']= $file;
						$_SESSION['url_img']=$url;
						header("Location: insert_blog.php?id=$id");
					}
					
				}
				else {
					echo "Файл не картинка, либо картинка не того формата. Допускается формат:jpg,png";
				}
				
			}
			else {
				echo "Файл больше 1Мб";
			}
			
				
				
				if(isset($_POST['submit'])){
					$title = $_POST['title'];
					$desc = $_POST['description'];
					$date = $_POST['date'];
					$img = $_POST['img'];
					$pop = '1';
					$aktiv = 0;
					$text = $_POST['text'];
					$id= $_POST['id'];
					$query = "INSERT INTO blog (`id`, `id_user`, `title`, `description`, `date`, `text`, `img`, `pop`, `aktiv`) 
					VALUES (NULL,'$id','$title', '$desc', '$date','$text','$img','$pop','$aktiv')";
			if (mysql_query($query,$db)){
				echo "<p>Запись произведен!</p>";
			}
			else {
				echo "Не удалось вставить";
			}
			
				}
			
				
		
			
			
		}
		else {
			echo 'Вы не верно вели пароль или email либо вошли без входа на страничку.';
		}
	?>
</div>


</body>
</html>
