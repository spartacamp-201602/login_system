<?php 
require_once('config.php');
require_once('functions.php');

session_start();
//sessionのidにデータがなければリダイレクト
if(empty($_SESSION['id'])){
	header('Location:login.php');
	exit;
}
	$dbh = connectDatabase();
	$sql="select * from users where id=:id";
	$stmt=$dbh->prepare($sql);

	$stmt->bindParam(":id",$_SESSION['id']);

	$stmt->execute();
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	$name=$row['name'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員限定画面</title>
</head>
<body>
    <h1>登録したユーザーのみ閲覧可能です！</h1>
    <h2>ようこそ<?php echo $name ?>さん。</h2>
    <p><a href='logout.php'>ログアウト</a></p>
</body>
</html>