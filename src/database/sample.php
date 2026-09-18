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

try {
    // ダミーデータの挿入
    $articleSql = <<<EOF
        INSERT INTO articles
            (title, body, user_id)
        VALUES
            ('サンプル', 'サンプルコードです', 1)
    EOF;
    $sth = $dbh->exec($articleSql);
    $articleId = $dbh->lastInsertId();

    $pivotSql = <<<EOF
        INSERT INTO articles_tags
            (article_id, tag_id)
        VALUES
            (:article_id, :tag_id_1),
            (:article_id, :tag_id_2);
    EOF;

    $sth = $dbh->prepare($pivotSql);

    $sth->execute([
        ':article_id' => (int) $articleId,
        ':tag_id_1'   => 1,
        ':tag_id_2'   => 3,
    ]);
    echo "ダミーデータを作成しました" . PHP_EOL;
} catch (Exception) {
    echo "ダミーデータの作成に失敗しました" . PHP_EOL;
}
