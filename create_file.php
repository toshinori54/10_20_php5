<?php

session_start();
include("functions.php");
check_session_id();


if (
    !isset($_POST['username']) || $_POST['username'] == '' ||
    !isset($_POST['telphone']) || $_POST['telphone'] == ''
) {
    // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
    echo json_encode(["error_msg" => "no input"]);
    exit();
}

// 受け取ったデータを変数に入れる
$username = $_POST['username'];
$telphone = $_POST['telphone'];
$reservation = $_POST['reservation'];
$request = $_POST['request'];

//ファイルアップロード & DB登録の処理を追加
if (!isset($_FILES['upfile']) && $_FILES['upfile']['error'] != 0) {
    // 送られていない，エラーが発生，などの場合
    exit('Error:画像が送信されていません');
} else {
    //ファイル名の取得
    $uploaded_file_name = $_FILES['upfile']['name'];
    //tmpフォルダの場所
    $temp_path  = $_FILES['upfile']['tmp_name'];
    //アップロード先ォルダ（↑自分で決める）
    $directory_path = 'upload/';

    $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
    $unique_name = date('YmdHis') . md5(session_id()) . "." . $extension;
    $filename_to_save = $directory_path . $unique_name;

    if (!is_uploaded_file($temp_path)) {
        exit('Error:画像がありません'); // tmpフォルダにデータがない
    } else { //  ↓ここでtmpファイルを移動する
        if (!move_uploaded_file($temp_path, $filename_to_save)) {
            exit('Error:アップロードできませんでした'); // 画像の保存に失敗
        } else {
            chmod($filename_to_save, 0644); // 権限の変更
            // 今回は権限を変更するところまで
        }
    }
}

// DB接続
$pdo = connect_to_db();

// データ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO todo_table(id, username, telphone,reservation,request, image, created_at, updated_at) 
VALUES(NULL, :username, :telphone,:reservation,:request, :image, sysdate(), sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':telphone', $telphone, PDO::PARAM_STR);
$stmt->bindValue(':reservation', $reservation, PDO::PARAM_STR);
$stmt->bindValue(':request', $request, PDO::PARAM_STR);
$stmt->bindValue(':image', $filename_to_save, PDO::PARAM_STR);
$status = $stmt->execute();
// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    header("Location:seitai_input.php");
    exit();
}
