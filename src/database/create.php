<?php

require_once __DIR__ . './../app/DatabaseConnector.php';

// CREATE文を定義
$createUsers = <<<EOF
    CREATE TABLE users (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
EOF;

$createTags = <<<EOF
    CREATE TABLE tags (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT uq_tags UNIQUE (name)
    );
EOF;

$createArticles = <<<EOF
    CREATE TABLE articles (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        user_id INT NOT NULL,
        likes_count INT NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
EOF;

$createArticlesTags = <<<EOF
    CREATE TABLE articles_tags (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        article_id INT NOT NULL,
        tag_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_articles_tags_articles FOREIGN KEY (article_id) REFERENCES articles(id),
        CONSTRAINT fk_articles_tags_tags FOREIGN KEY (tag_id) REFERENCES tags(id)
    );
EOF;

// DB接続処理
$databaseConnector = new DatabaseConnector([
    'hostname' => 'db',
    'database' => 'team_dev',
    'username' => 'team_user',
    'password' => 'pass',
]);
$dbh = $databaseConnector->getConnection();

$queries = [
    'createUsers' => $createUsers,
    'createTags' => $createTags,
    'createArticles' => $createArticles,
    'createArticlesTags' => $createArticlesTags,
];

// テーブル作成
try {
    foreach ($queries as $query) {
        $dbh->exec($query);
    }
    echo "テーブルを作成しました" . PHP_EOL;
} catch (Exception $e) {
    echo "テーブル作成に失敗しました。" . $e->getMessage();
}
