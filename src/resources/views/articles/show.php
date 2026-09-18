<?php

/** @var string $id */
/** @var string $title */
/** @var string $body */
/** @var array $checkedTags */
/** @var bool $isAuthor */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事閲覧</title>
</head>

<body>
    <h2>記事閲覧ページ</h2>
    <p>タイトル</p>
    <p><?= $title ?></p>
    <p>本文</p>
    <p><?= $body ?></p>
    <p>タグ</p>
    <ul>
        <?php foreach ($checkedTags as $checkedTag) :?>
            <li><?= $checkedTag['id'] . ':' . $checkedTag['name'] ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="/articles/edit?id=<?= $id ?>">編集する</a>
    <form action="/articles/delete?id=<?= $id ?>" method="POST">
        <button type="submit">削除する</button></form>
</body>

</html>
