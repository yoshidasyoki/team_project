<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
</head>

<body>
    <h2>ログインページ（仮実装）</h2>

    <?php if (!empty($_SESSION['flashMessage']['errors'])): ?>
        <ul>
            <?php foreach ($_SESSION['flashMessage']['errors'] as $error) : ?>
                <li><?php echo $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="/login/auth" method="POST">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>

    <a href="/users/create">新規登録</a>
</body>

</html>
