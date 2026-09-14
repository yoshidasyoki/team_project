<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規作成</title>
</head>

<body>
    <h2>ユーザ新規登録ページ</h2>
    <?php if (!empty($_SESSION['flashMessage']['errors'])): ?>
        <ul>
            <?php foreach ($_SESSION['flashMessage']['errors'] as $error) : ?>
                <li><?php echo $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="/users/create/store" method="POST">
        <input type="text" name="username" placeholder="ユーザー名を入力">
        <input type="password" name="password" placeholder="パスワードを入力">
        <button type="submit">登録</button>
    </form>
</body>

</html>
