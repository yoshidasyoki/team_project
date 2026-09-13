<?php

/** @var array $tags */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タグ管理</title>
</head>

<body>
    <h2>タグページ管理</h2>
    <form action="/tags/store" method="POST">
        <input type="text" name="tag" placeholder="タグを入力">
        <button type="submit">タグ追加</button>
    </form>

    <ul>
        <?php foreach ($tags as $tag) : ?>
            <li><?php echo $tag['name'] ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>
