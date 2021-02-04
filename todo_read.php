<?php
session_start();
include("functions.php");
check_session_id();

$user_id = $_SESSION["id"];


// DB接続
$pdo = connect_to_db();

// データ取得SQL作成
$sql = "SELECT * FROM todo_table";
$sql = " SELECT * FROM todo_table 
LEFT OUTER JOIN (SELECT todo_id, COUNT(id) AS cnt
 FROM like_table GROUP BY todo_id) AS likes
  ON todo_table.id = likes.todo_id";



// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    // fetchAll()関数でSQLで取得したレコードを配列で取得できる
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
    $output = "";
    // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
    // `.=`は後ろに文字列を追加する，の意味
    foreach ($result as $record) {
        $output .= "<tr>";
        $output .= "<td>{$record["username"]}</td>";
        $output .= "<td>{$record["telphone"]}</td>";
        $output .= "<td>{$record["reservation"]}</td>";
        $output .= "<td>{$record["request"]}</td>";

        // edit deleteリンクを追加
        //テーブルを結合した後はカラム名「 cnt 」を指定していいね件数を取れる！！

        $output .= "<td><a href='like_create.php?user_id={$user_id}&todo_id={$record["id"]}'>いいね{$record["cnt"]}</a></td>";
        $output .= "<td><a href='todo_edit.php?id={$record["id"]}'>edit</a></td>";
        // $output .= "<td><a href='todo_gazou.php?id={$record["id"]}'>画像</a></td>";
        $output .= "<td><a href='todo_delete.php?id={$record["id"]}'>delete</a></td>";
        // 画像出力を追加しよう
        $output .= "<td><img src='{$record["image"]}' height=150px></td>";
        $output .= "</tr>";
    }
    // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
    // 今回は以降foreachしないので影響なし
    unset($value);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">

    <!-- お試しいいねボタン用 -->
    <!-- <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet"> -->
    <title>DB連携型予約者リスト（一覧画面）</title>
</head>

<body>
    <fieldset>
        <legend>DB連携型予約者リスト（一覧画面）</legend>
        <a href="kanri_input.php">入力画面</a>
        <a href="todo_logout.php">logout</a>
        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>連絡先</th>
                    <th>予約希望美</th>
                    <th>要望</th>
                    <th> </th>
                    <th>修正</th>
                    <th>削除</th>
                    <th>問診表</th>
                </tr>
            </thead>
            <tbody>
                <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
                <?= $output ?>

            </tbody>
        </table>
    </fieldset>
</body>

</html>