<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
</head>

<body>
    <!-- ナビゲーションメニュー・ボタンエリア -->
    <header class="header">
        <div class="header-left">
            <h2>ホーム画面</h2>
            <p>ログインユーザー: <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <!-- マイページ・タグ管理のメニュー -->
        <nav class="header-nav">
            <ul>
                <li><a href="/mypage">マイページ</a></li>
                <li><a href="/tags">タグ管理</a></li>
            </ul>
        </nav>

        <!-- 独立した新規登録ボタンエリア -->
        <div class="header-action">
            <a href="/articles/create" class="btn-create">＋新規登録</a>
        </div>
    </header>


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
</body>

</html>