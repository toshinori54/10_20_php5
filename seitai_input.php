<?php
// session_start();
include("functions.php");
// check_session_id();
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/input.css">
    <title> 予約ページ（予約者専用）</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>



    <!-- <form action="todo_create.php" method="POST"> -->
    <!-- アップロードフォームの追加 -->
    <form action="create_file.php" method="POST" enctype="multipart/form-data">
        <fieldset class="form">
            <h1>
                <legend>予約フォーム（予約者専用）</legend>
            </h1>
            <!-- <a href="todo_read.php">一覧画面</a> -->
            <a href="top_page.php">戻る</a>
            <!-- <a href="todo_read.php">一覧画面</a> -->
            <!-- <a href="todo_logout.php">logout</a> -->
            <div class="container">
                <div class="input_form">


                    <div>
                        <label>お名前</label>
                        <input type="text" name="username" placeholder="山田　太郎">
                    </div>
                    <div>
                        <label>連絡先</label>
                        <input type="text" name="telphone" placeholder="090-xxxx-oooo">
                    </div>
                    <div>
                        <label>予約日</label>
                        <label class="date-edit"><input type="date" name="reservation"></label>
                    </div>
                    <div id="hope">
                        <label>要望事項</label>
                        <textarea name="request" placeholder="気になる症状がある方は内容を記入ください">
                    </textarea>
                    </div>
                    <!-- 画像挿入 -->
                    <!-- <div>
                    画像添付: <input type="file" name="upfile" accept="image/*" capture="camera">
                </div> -->

                    <div>
                        <button>submit</button>
                    </div>
                </div>


        </fieldset>
    </form>

    </div>
</body>

</html>