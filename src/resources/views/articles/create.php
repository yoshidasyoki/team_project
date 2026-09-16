<?php

/** @var array $tags */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規投稿</title>
</head>

<body>
    <h2>新規投稿ページ</h2>
    <form action="/articles/store" method="POST">
        <p>タイトル</p>
        <input type="text" name="title" placeholder="タイトルを入力">
        <p>タグ</p>

        <?php foreach ($tags as $tag): ?>
            <input type="checkbox" id="<?= $tag['id'] ?>" name="tags[]" value="<?= $tag['id'] ?>" />
            <label for="<?= $tag['id'] ?>"><?= $tag['name'] ?></label>
        <?php endforeach; ?>
        <p>本文</p>
        <input type="text" name="body" placeholder="本文を入力">
        <button type="submit">投稿</button>
    </form>
</body>

</html>
