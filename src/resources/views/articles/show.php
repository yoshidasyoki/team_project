<?php

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
    <p><?= $title ?></p>
    <p><?= $body ?></p>
    <ul>
        <?php foreach ($checkedTags as $checkedTag) :?>
            <li><?= $checkedTag['id'] . ':' . $checkedTag['name'] ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>
