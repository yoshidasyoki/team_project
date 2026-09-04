<?php
/** @var string $message */
/** @var string $timestamp */
/** @var array $lists */
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test page</title>
</head>
<body>
    <h2>This is a test page.</h2>
    <p><?php echo $message; ?></p>
    <p>Timestamp: <?php echo $timestamp; ?></p>
    <ul>
        <?php foreach ($lists as $item): ?>
            <li><?php echo $item; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
