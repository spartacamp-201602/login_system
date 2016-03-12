<?php
require_once('config.php');
require_once('functions.php');
session_start();
// if(empty($_SESSION['id'])){
// 	header('Location:login.php');
// 	exit;
// }

	$errors=array();

	$dbh=connectDatabase();
	$sql="select * from users where id=:id;";
	$stmt=$dbh->prepare($sql);
	$stmt->bindParam(':id',$_SESSION['id']);
	$stmt->execute();
	$base=$stmt->fetch(PDO::FETCH_ASSOC);
	
	$name2=$base['name'];
	$password2=$base['password'];
if($_SERVER['REQUEST_METHOD']=='POST'){
	$name=$_POST['name'];
	$password=$_POST['password'];
if(empty($name)){
	$errors[]='ユーザー名が未入力です';
}
if(empty($password)){
	$errors[]='パスワードが未入力です';
}
if(empty($errors)){
	$dbh=connectDatabase();
	$sql="select * from users where name=:name;";
	$stmt=$dbh->prepare($sql);
	$stmt->bindParam(':name',$name);
	$stmt->execute();
	$same=$stmt->fetch(PDO::FETCH_ASSOC);
//var_dump($same);
	if($same){
		$errors[]= 'ユーザーネーム"'.$same['name'].'"は登録済みです';
	}else{
		$sql='insert into users (name,password,created_at)values(:name,:password,now());';
		$stmt=$dbh->prepare($sql);
		$stmt->bindParam(':name',$name);
		$stmt->bindParam(':password',$password);
		$stmt->execute();
		header('Location:login.php');
		exit;
	}
}
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録画面</title>
</head>
<style>
	.error{
		color:red;
		list-style: none;
	}
</style>
<body>
    <h1>ユーザー情報編集</h1>

    <?php if(isset($errors)):?>
    	<div class="error">
    		<?php foreach ($errors as $error):?>
    			<li><?php echo $error;?></li>
    		<?php endforeach;?>
    	</div>
    <?php endif;?>

    <div>
    	<?php 
    	echo '現在のユーザーネーム：'.$name2.'<br>';
    	echo '現在のパスワード：'.$password2.'<br>';?>
    </div>

    <form action="" method="post">
        ユーザネーム: <input type="text" name="name"><br>
        パスワード: <input type="text" name="password"><br>
        <input type="submit" value="新規登録">
    </form>
    <a href="login.php">ログインはこちら</a>
</body>
</html>

