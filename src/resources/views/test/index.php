<?php
    /** @var string $username */
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <p><?php echo $username; ?></p>
    <h2>Hello, World!</h2>
    <form action="/logout" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
