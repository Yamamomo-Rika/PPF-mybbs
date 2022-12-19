<?php
require('pdo_connect.php');

$input_name = $_POST['input_name'];
$input_text = nl2br($_POST['input_text']);
$popup_message = null;

$sql_input_data = "INSERT INTO posts
SET post_id='', post_name='$input_name', post_text='$input_text', post_datetime=NOW()";

//入力確認
if (strlen($input_text) >= 1 && strlen($input_text) <= 500 && strlen($input_name) >= 1) { //本文1字以上500字以下、投稿者名あり
    $statement = $dbh->prepare($sql_input_data);
    $statement->execute(array($input_text));
    $popup_message = '投稿が完了しました！';
} elseif (strlen($input_text) == 0 || strlen($input_text) > 500) { //投稿文がない、または500字より大きい
    $popup_message = '投稿エラー：投稿文は500字以内で入力してください。';
} elseif (strlen($input_name) == 0 || strlen($input_name) > 64) { //投稿者名がない、または64字より大きい
    $popup_message = '投稿エラー：投稿者名は64字以内で入力してください。';
} else { //その他のエラー
    $popup_message = '投稿エラー：投稿に失敗しました。もう一度内容をご確認いただき、再度投稿してください。';
}
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
<header>
    <div class="header__wrapper">
        <h1 class="logo logo__header"><a href="http://yamamomo101.php.xdomain.jp/mybbs/">MyBBS</a></h1>
    </div>
    <div class="header__nav">
        <p>みんなが自由に投稿できる掲示板</p>
    </div>
</header>

    <main>
        <div class="popup__container"><!-- 投稿後ポップアップメッセージ -->
            <div class="popup__box">
                <p><?php echo $popup_message; ?></p>
                <div class="flex__end">
                    <a class="btn__popup btn__gray" href="http://yamamomo101.php.xdomain.jp/mybbs/"><i class="fa-solid fa-xmark"></i> 閉じる</a>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <article class="main-contents">
                <section class="post__list__container">
                    <ul class="post__list">
                        <?php
                        $sql_get_data = 'SELECT * FROM posts ORDER BY post_id DESC';
                        $results = $dbh->query($sql_get_data);
                        ?>
                        <?php foreach($results as $data) { ?>
                            <li>
                                <div class="post__head">
                                    <div class="_left">
                                        <p class="post-name"><?php echo $data['post_name']; ?></p>
                                        <p class="post-datetime"><?php echo $data['post_datetime']; ?></p>
                                    </div>
                                    <div class="_right">
                                        <p class="btn__edit"><i class="fa-solid fa-pen"></i> 編集</p>
                                        <p class="btn__reply"><i class="fa-solid fa-reply"></i> 返信</p>
                                    </div>
                                </div>
                                <div class="post__content">
                                    <p class="post-text"><?php echo $data['post_text']; ?></p>
                                </div>
                            </li>
                        <?php } ?> 
                    </ul>
                </section>
            </article>

            <aside class="sidebar" id="draft">
                <h2>コメントを投稿する</h2>
                <form name="form_input_post" action="input_post.php" method="POST" onsubmit="return confirm_test()">
                    <p>投稿者名</p>
                    <input type="text" name="input_name" placeholder="投稿者名を入力（64字以内）">
                    <textarea name="input_text" id="input_text"" cols="30" rows="10" placeholder="コメントをお書きください。"></textarea>
                    <div class="flex__center">
                        <input type="submit" name="submit" class="btn__primary" value="コメントを投稿する">
                    </div>
                </form>
            </aside>

            <div class="btn-fixed sp-only">
                <a href="#draft" id="to-draft" class="btn__to-draft"></a>
            </div>
        </div>
    </main>

    <footer id="footer">
        <div class="wrapper footer__inner">
            <h1 class="logo logo__footer"><a href="http://yamamomo101.php.xdomain.jp/mybbs/">MyBBS</a></h1>
            <ul class="footer__nav">
                <li><a href="#">運営会社</a></li>
                <li><a href="#">お問い合わせ</a></li>
                <li><a href="#">利用規約</a></li>
            </ul>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>