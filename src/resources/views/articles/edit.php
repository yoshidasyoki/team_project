<?php

/** @var string $id */
/** @var string $title */
/** @var string $body */
/** @var array $tags */
/** @var array $checkedTags */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集ページ</title>
</head>

<body>
    <form action="/articles/update?id=<?= $id ?>" method="POST">
        <div>
            <label for="">タイトル</label>
            <input type="text" name="title" value="<?= $title ?>">
        </div>

        <?php foreach ($tags as $tag): ?>
            <label>
                <input
                    type="checkbox"
                    name="tags[]"
                    value="<?= $tag['id'] ?>"
                    <?= in_array($tag['id'], array_map(fn($tag) => $tag['id'], $checkedTags), true) ? 'checked' : '' ?>>

                <?= htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8') ?>
            </label>
        <?php endforeach; ?>

        <div>
            <label for="">本文</label>
            <textarea name="body" id="body"><?= $body ?></textarea>
        </div>

        <button type="submit">更新</button>
    </form>
</body>

</html>
