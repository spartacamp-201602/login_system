<?php
require_once('config.php');
require_once('functions.php');
$i;
if($_SERVER['REQUEST_METHOD']=='POST'){
	$name=$_POST['name'];
	$password=$_POST['password'];
	$errors=array();
	if(empty($name)){
		$errors[]='ユーザー名が未入力です！';
	}
	if(empty($password)){
		$errors[]='パスワードが未入力です！';
	}
	if(strlen($name)<4){
		for($i=0;$i<=4-strlen($name);$i++){
			$errors[]='ユーザーネームが短すぎます！';
		}
	}
	if(strlen($name)>4){
		for($i=0;$i<=strlen($name)-4;$i++){
			$errors[]='ユーザーネームが長すぎます！';
		}
	}
	if(strlen($password)<4){
		for($i=0;$i<=4-strlen($password);$i++){
			$errors[]='パスワードが短すぎます！';
		}
	}
	if(strlen($password)>4){
		for($i=0;$i<=strlen($password)-4;$i++){
			$errors[]='パスワードが長すぎます！';
		}
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
			$errors[]= 'ユーザーネーム"'.$same['name'].'"は登録済みです！';
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
    <h1>新規ユーザー登録</h1>

    <?php if(isset($errors)):?>
    	<div class="error">
    		<h2><?php foreach ($errors as $error):?>
    			<li><?php echo $error;?></li>
    		<?php endforeach;?></h2>
    	</div>
    <?php endif;?>
    <form action="" method="post">
        ユーザネーム: <input type="text" name="name" value="<?php echo $name;?>"><br>
        パスワード: <input type="text" name="password" value="<?php echo $password;?>"><br>
        <input type="submit" value="新規登録">
    </form>
    <a href="login.php">ログインはこちら</a>
</body>
</html>

