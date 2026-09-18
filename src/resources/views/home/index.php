<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
</head>

<body>
    <h2>ホーム画面</h2>

    <!-- 例: ログイン中のユーザー名を出力 -->
    <p>ログインユーザー: <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></p>


    <div class="article-list">
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <div class="article-header">
                    <div>
                        <div class="author-name"><?= htmlspecialchars($article['author_name'] ?? 'ユーザー1') ?></div>
                        <div class="created-at"><?= htmlspecialchars($article['created_at'] ?? '12:34') ?></div>
                    </div>
                    <!-- タグ（複数ある場合はカンマ区切り、またはリンク） -->
                    <?php if (!empty($article['tags'])): ?>
                        <span class="tag-label"><?= htmlspecialchars($article['tags']) ?></span>
                    <?php endif; ?>
                </div>

                <h2 class="article-title">
                    <a href="/articles/detail?id=<?= urlencode($article['id']) ?>">
                        <?= htmlspecialchars($article['title']) ?>
                    </a>
                </h2>

                <div class="article-footer">
                    <span class="star-icon">☆</span>
                    <span><?= htmlspecialchars($article['likes_count'] ?? 0) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <form action="/logout" method="POST">
        <button type="submit">ログアウト</button>
    </form>
</body>

</html>
