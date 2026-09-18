<?php

require_once __DIR__ . './../app/DatabaseConnector.php';

// DB接続
$databaseConnector = new DatabaseConnector([
    'hostname' => 'db',
    'database' => 'team_dev',
    'username' => 'team_user',
    'password' => 'pass',
]);
$dbh = $databaseConnector->getConnection();

// 初期状態のタグを自動生成する
$insertTags = <<<EOF
    INSERT INTO tags (name) VALUES
        ('Ruby'), ('PHP'), ('JavaScript'), ('HTML/CSS'), ('SQL');
EOF;

try {
    $dbh->exec($insertTags);
    echo "データを作成しました。" . PHP_EOL;
} catch (Exception $e) {
    echo "データの作成に失敗しました。" . $e->getMessage();
}
