<p>コメントしてください。</p>
  <form method="POST" action="sql.php">
  <input name="name" /> 
  <input name="comment" />
  <input type="submit" value="送信" />
</form>

<?php
require '../password.php';
//mysqliクラスのオブジェクトを作成
if($_SERVER['SERVER_NAME'] == "localhost") {
  $mysqli = new mysqli($db['host'], $db['user'], '', $db['dbname']);
} else {
	//conohaサーバの接続設定
	$mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);
}
//エラーが発生したら
if ($mysqli->connect_error){
  print("接続失敗：" . $mysqli->connect_error);
}

if ((empty($_POST["name"]) && (empty($_POST["comment"])))) {
  print("名前, コメントがありません</br>");
}
elseif ((empty($_POST["name"]))) {
  print("名前がありません</br>");
} elseif ((empty($_POST["comment"]))) {
  print("コメントがありません</br>");
} else {
  //プリペアドステートメントを作成
  $stmt = $mysqli->prepare("INSERT INTO datas (name, comment) VALUES (?, ?)");
  //?の位置に値を割り当てる
  $stmt->bind_param('ss', $_POST["name"], $_POST["comment"]);
  //ステートメント実行
  $stmt->execute();
  //echo $_SERVER['SERVER_NAME'];
}
$result = $mysqli->query("SELECT * FROM datas ORDER BY created DESC");
if($result){
  //1行ずつ取り出し
  while($row = $result->fetch_object()){
    //エスケープして表示
    $name = htmlspecialchars($row->name);
    $comment = htmlspecialchars($row->comment);
    $created = htmlspecialchars($row->created);
    print("$name : $comment ($created)<br>");
  }
}
?>

