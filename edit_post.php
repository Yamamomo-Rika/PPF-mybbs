<?php
require('pdo_connect.php');

if( !empty($_GET['post_id']) && empty($_POST['post_id']) ) {
	$results = $dbh->prepare('SELECT * FROM posts WHERE post_id = :id');
	$results->bindValue( ':id', $_GET['post_id'], PDO::PARAM_INT);
	$results->execute();
    $post_data = $results->fetch();
} elseif ( !empty($_POST['post_id']) ) {
    // 空白を除去
	$input_name = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['input_name']);
	$input_text = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['input_text']);
	// 表示名の入力チェック
	if( empty($input_name) ) {
		$error_message[] = '表示名を入力してください。';
	}
	// メッセージの入力チェック
	if( empty($input_text) ) {
		$error_message[] = 'メッセージを入力してください。';
	}
	if( empty($error_message) ) {
		$dbh->beginTransaction();
		try {
			$results = $dbh->prepare("UPDATE posts SET post_name = :post_name, post_text= :post_text, post_datetime=NOW() WHERE post_id = :post_id");
			$results->bindParam( ':post_name', $input_name, PDO::PARAM_STR);
			$results->bindParam( ':post_text', $input_text, PDO::PARAM_STR);
			$results->bindValue( ':post_id', $_POST['post_id'], PDO::PARAM_INT);

            $results->execute();
			$res = $dbh->commit();
		} catch(Exception $e) { //エラーが出たらロールバック
			$dbh->rollBack();
        }
        
		if( $res ) {
			header("Location: ./"); //投稿完了後TOPにもどる
			exit;
		}
	}
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

    <main>
        <div class="popup__container edit-page">
            <div class="popup__box">
                <h2>投稿を編集する</h2>
                <br>
                <form method="post">
                    <div>
                        <label for="input_name">投稿者名</label>
                        <input id="input_name" type="text" name="input_name" value="<?php if( !empty($post_data['post_name']) ){ echo $post_data['post_name']; } ?>">
                    </div>
                    <div>
                        <label for="input_text">投稿内容</label>
                        <textarea id="input_text" name="input_text" rows="5"><?php if( !empty($post_data['post_text']) ){ echo $post_data['post_text']; } ?></textarea>
                    </div>

                    <a class="btn__delete" href="delete_post.php?id=<?php echo($post_data['post_id']); ?>"><i class="fa-solid fa-xmark"></i> 投稿を削除する</a>
                    
                    <div class="flex__center mt40">
                        <a class="btn__gray" href="http://yamamomo101.php.xdomain.jp/mybbs/">キャンセル</a>
                        <input class="btn__primary" type="submit" name="btn_submit" value="内容を更新する">
                    </div>

                    <input type="hidden" name="post_id" value="<?php if( !empty($post_data['post_id']) ){ echo $post_data['post_id']; } ?>">
                </form>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php $dbh = null; ?>