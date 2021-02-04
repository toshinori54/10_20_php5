<?php

if (!isset($_FILES['upfile']) && $_FILES['upfile']['error'] != 0) {
    //送信されていない得れーの場合
    exit('Error:画像が送信されていません');
    //送信成功の場合
} else {
    //ファイル名の取得
    $uploaded_file_name = $_FILES['upfile']['name'];
    //tmpフォルダの場所
    $temp_path  = $_FILES['upfile']['tmp_name'];
    //アップロード先ォルダ（↑自分で決める）
    $directory_path = 'upload/';

    // ファイルの拡張子の種類を取得．
    // ファイルごとにユニークな名前を作成．（最後に拡張子を追加）
    // ファイルの保存場所をファイル名に追加．

    $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
    $unique_name = date('YmdHis') . md5(session_id()) . "." . $extension;
    $filename_to_save = $directory_path . $unique_name;

    //サーバー保存領域に移動
    if (!is_uploaded_file($temp_path)) {
        // tmpフォルダにデータがない
        exit('Error:画像がありません');
        //  ↓ここでtmpファイルを移動する
    } else {
        if (!move_uploaded_file($temp_path, $filename_to_save)) {
            // 画像の保存に失敗
            exit('Error:アップロードできませんでした');
        } else {
            // 権限の変更
            chmod($filename_to_save, 0644);
            // imgタグを設定
            $img = '<img src="' . $filename_to_save . '" >';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>file_upload</title>
</head>

<body>
    <!-- ここに画像を表示しよう -->
    <?= $img ?>
</body>

</html>