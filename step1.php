<link rel="stylesheet" href="style.css">
<p><strong>掲示板</strong></p>
  <form method="POST" action="step1.php">
    <div>
      <label>名前: </label>
      <input name="name" />
    </div>
    <div>
    <label>コメント: </label>
      <input name="comment" />
    </div>
    <div class="button">
      <button type="submit">送信</button>
    </div>
  </form>

<?php
require '../password.php';
//mysqliクラスのオブジェクトを作成
//echo $_SERVER['SERVER_NAME'];
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
  $postname = strip_tags($_POST["name"]);
  
  //scriptタグを削除
  $postcomment = strip_tags($_POST["comment"]);
  //改行コードを<br>に置換
  $postcommentbr = nl2br($postcomment);

  $stmt->bind_param('ss', $postname, $postcommentbr);
  //ステートメント実行
  $stmt->execute();
  
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

