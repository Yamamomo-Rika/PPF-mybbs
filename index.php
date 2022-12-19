<?php require('pdo_connect.php');

//1ページに表示する件数
define('max_view',10);

//必要なページ数を求める
$count = $dbh->prepare('SELECT COUNT(*) AS count FROM posts');
$count->execute();
$total_count = $count->fetch(PDO::FETCH_ASSOC);
$pages = ceil($total_count['count'] / max_view);

//現在いるページのページ番号を取得
if(!isset($_GET['page_id'])){ 
    $now = 1;
}else{
    $now = $_GET['page_id'];
}

$sql_get_data = "SELECT * FROM posts ORDER BY post_id DESC LIMIT :start,:max";
$results = $dbh->prepare($sql_get_data);
                                                                                                           
if ($now == 1){//1ページ目の処理
    $results->bindValue(":start",$now -1,PDO::PARAM_INT);
    $results->bindValue(":max",max_view,PDO::PARAM_INT);
} else { //1ページ目以外の処理
    $results->bindValue(":start",($now -1 ) * max_view,PDO::PARAM_INT);
    $results->bindValue(":max",max_view,PDO::PARAM_INT);
}
$results->execute();
$result = $results->fetchAll(PDO::FETCH_ASSOC); //DBからデータを取り出した列
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
        <div class="wrapper">
            <!-- MAIN -->
            <article class="main-contents">
                <section class="post__list__container">
                    <ul class="post__list">
                        <?php foreach($result as $data) { ?>
                            <li>
                                <div class="post__head">
                                    <div class="_left">
                                        <p class="post-name"><?php echo $data['post_name']; ?></p>
                                        <p class="post-datetime"><?php echo $data['post_datetime']; ?></p>
                                    </div>
                                    <div class="_right">
                                        <a class="btn__edit" href="edit_post.php?post_id=<?php echo $data['post_id']; ?>"><i class="fa-solid fa-pen"></i> 編集</a>
                                        <p class="btn__reply"><i class="fa-solid fa-reply"></i> 返信</p>
                                    </div>
                                </div>
                                <div class="post__content">
                                    <p class="post-text"><?php echo $data['post_text']; ?></p>
                                </div>
                            </li>
                        <?php } ?> 
                    </ul>

                    <ul class="page-nation">
                    <?php //ページネーション
                    for ($n = 1; $n <= $pages; $n ++){
                        if ( $n == $now ){ ?>
                            <li class="current"><span><?php echo $now; ?></span></li>
                        <?php }else{ ?>
                            <li><a href='http://yamamomo101.php.xdomain.jp/mybbs/?page_id=<?php echo $n; ?>'><?php echo $n; ?></a></li>
                        <?php } 
                    } ?>
                    </ul>
                </section>
            </article>

            <!-- SIDEBAR -->
            <aside class="sidebar" id="draft">
                <div class="sidebar__inner">
                    <h2>コメントを投稿する</h2>
                    <form name="form_input_post" action="input_post.php" method="POST" onsubmit="return confirm_test()">
                        <p>投稿者名</p>
                        <input type="text" name="input_name" placeholder="投稿者名を入力（64字以内）">
                        <textarea name="input_text" id="input_text"" cols="30" rows="10" placeholder="コメントをお書きください。"></textarea>
                        <div class="flex__center">
                            <input type="submit" name="submit" class="btn__primary" value="コメントを投稿する">
                        </div>
                    </form>
                </div>
            </aside>

            <!-- SP版固定ボタン -->
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