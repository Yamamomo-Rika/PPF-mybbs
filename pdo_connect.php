<?php

// ドライバ呼び出しを使用して MySQL データベースに接続します
$dsn = 'mysql:host=mysql1.php.xdomain.ne.jp;dbname=yamamomo101_db';
$posts = 'yamamomo101_user@sv2.php.xdomain.ne.jp';
$password = 'yamamomo101user';

try {
    $dbh = new PDO($dsn, $posts, $password);
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}