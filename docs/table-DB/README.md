## テーブル設計

```sql
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255)  NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

```jsx
CREATE TABLE articles (
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(255) NOT NULL,
tags_id INT NOT NULL,
body TEXT NOT NULL,
posted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
users_id INT NOT NULL,
likes_count INT NOT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_articles_tag FOREIGN KEY (tags_id) REFERENCES tags(id),
CONSTRAINT fk_articles_user FOREIGN KEY (users_id) REFERENCES users(id)
);
```

```jsx
CREATE TABLE tags (
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT uq_tags UNIQUE (name)
);
```

```jsx
CREATE TABLE articles_tags (
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
articles_id INT NOT NULL,
tags_id INT NOT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_articles_tags_articles FOREIGN KEY (articles_id) REFERENCES articles(id),
CONSTRAINT fk_articles_tags_tags FOREIGN KEY (tags_id) REFERENCES tags(id)
);
```



### ダミーデータ挿入

```jsx
- 1. users（ユーザー）テーブル
INSERT INTO users (name, password) VALUES('山田太郎', 'hashed_password_123'),('佐藤花子', 'hashed_password_456');
```

```jsx
- 2. tags（タグ）テーブル
INSERT INTO tags (name) VALUES
('programing'),
('MySQL'),
('Web開発');
```

```jsx
- 3. articles（記事）テーブル
-- ※id, posted_at, created_at は自動入力されるため省略しています
INSERT INTO articles (title, tags_id, body, users_id, likes_count) VALUES
('MySQLの基礎知識', 2, 'データベースの設計とSQLの書き方について解説します。', 1, 5),
('チーム開発の始め方', 1, 'GitとGitHubを使ったスムーズな開発フローの解説。', 1, 12),
('Webアプリ制作入門', 3, 'HTML/CSSからバックエンド構築までのロードマップ。', 2, 0);
```

```jsx
- 4. articles_tags（中間）テーブル
INSERT INTO articles_tags (articles_id, tags_id) VALUES
(1, 2), -- 記事1 × タグ2 (MySQL)
(2, 1), -- 記事2 × タグ1 (プログラミング)
(2, 3), -- 記事2 × タグ3 (Web開発)
(3, 3); -- 記事3 × タグ3 (Web開発)
```

```jsx
INSERT INTO articles_tags (articles_id, tags_id) VALUES
(1, 2), 
(2, 1), 
(2, 3), 
(3, 3); 
```



































## ログイン/Signin

●DBから2つ(usersからname,password)の情報をもってくる

```jsx
SELECT name, password FROM users;
```

## ユーザー新規登録/Signup

●新規登録画面で入力されたユーザー情報を `users` テーブルに保存（挿入）する

```jsx
INSERT INTO users (name, password) VALUES ('新規ユーザー名', 'パスワード文字列');
```


## 一覧表示/home

●DBからデータを取ってくる
ユーザー名
タイトル
投稿時間
いいね数
タグ

●投稿時間で並び順きめる

●⭐️検索機能　WHERE

```jsx
SELECT 
    a.id AS 記事ID,
    u.name AS ユーザー名,
    a.title AS タイトル,
    a.posted_at AS 投稿時間,
    a.likes_count AS いいね数,
    t.name AS タグ
FROM 記事テーブル a
-- 頭文字のイニシャルはテーブルを指している
-- データを取り出す元のテーブルを指定する FROM や、テーブルを結合する JOIN の直後でそれぞれ定義（あだ名付け）をしている

-- ユーザー情報は常に必要なので INNER JOIN
JOIN Userテーブル u
    ON a.user_id = u.id

-- タグがない記事も残すため LEFT JOIN（外部結合）
LEFT JOIN 記事タグテーブル at
    ON a.id = at.article_id
LEFT JOIN タグテーブル t
    ON at.tag_id = t.id

-- ●投稿時間が新しい順
ORDER BY a.posted_at DESC;

-- ⭐️●検索条件（タイトル・本文・タグ名・ユーザー名のいずれかにマッチ）※タグ以外にも本文のワード含む
WHERE
    a.title LIKE '%検索ワード%'
    OR a.body LIKE '%検索ワード%'
    OR t.name LIKE '%検索ワード%'
    OR u.name LIKE '%検索ワード%'
    
-- ●特定のタグ名で検索(タグのみで検索したい場合)
WHERE t.name = 'Python'
ORDER BY a.posted_at DESC; 
```