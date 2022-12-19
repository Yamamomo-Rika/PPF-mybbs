<?php
require('pdo_connect.php');

$id = $_REQUEST['post_id'];
$delete = $dbh->prepare('DELETE FROM posts WHERE post_id=?');
$delete->execute(array($id));

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MyBBS - みんなの自由な掲示板</title>
    <meta name="description" content="会員登録なしでだれでも自由に投稿・返信ができる掲示板です。">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Display:wght@900&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/906a87091e.js" crossorigin="anonymous"></script><!-- Font Awesome 6 -->
    <link rel="stylesheet" href="assets/css/sanitize.css"><!-- リセットCSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <main>
        <div class="popup__container edit-page">
            <div class="popup__box">
                <h2>投稿を編集する</h2>
                <div class="flex__center">
                    <p>投稿を削除しました</p>
                </div>
                <div class="flex__center mt40">
                    <a class="btn__primary" href="http://yamamomo101.php.xdomain.jp/mybbs/">TOPに戻る</a>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php $dbh = null; ?>