<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apprentice Log｜学びを記録し、成長を可視化する</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="/resources/css/style.css">
</head>

<body class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('/resources/img/bg-img.png');">
    <div class="bg-white rounded-2xl shadow-sm p-10 w-full max-w-md z-10">

        <div class="flex items-center gap-3 justify-center mb-6">
            <img src="/resources/img/logo-green.svg" alt="Apprentice Log" class="w-12 h-12 rounded-xl">
            <div>
                <p class="text-lg font-bold">Apprentice Log</p>
                <p class="text-xs text-gray-400">学びを記録し、成長を可視化する</p>
            </div>
        </div>

        <p class="text-sm text-gray-400 text-center mb-6">アカウント登録して
            <br>新たな学習を始めましょう
        </p>

        <!-- エラーメッセージの表示 -->
        <ul>
            <?php if ($_SESSION['flashMessage']??null) : ?>
                <?php foreach ($_SESSION['flashMessage']['errors'] as $error) : ?>
                    <li class="px-3 pb-3 text-red-700">※ <?= $error ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <form action="/users/store" method="POST" class="flex flex-col gap-4">

            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" />
                </svg>
                <input type="text" name="username" placeholder="ユーザー名またはメールアドレス" required class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-[var(--color-primary)]">
            </div>

            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15V17M6 21H18C19.1046 21 20 20.1046 20 19V13C20 11.8954 19.1046 11 18 11H6C4.89543 11 4 11.8954 4 13V19C4 20.1046 4.89543 21 6 21ZM16 11V7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7V11H16Z" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" />
                </svg>
                <input type="password" name="password" id="password" placeholder="パスワード" required class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-[var(--color-primary)]">
                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.94 17.94A10.07 10.07 0 0112 20C7 20 2.73 16.39 1 12C1.69 10.24 2.81 8.69 4.19 7.46M9.9 4.24A9.12 9.12 0 0112 4C17 4 21.27 7.61 23 12C22.18 14.07 20.79 15.86 19.03 17.2M1 1L23 23M10.59 10.59A2 2 0 0013.41 13.41" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <button type="submit" class="w-full py-3 bg-[var(--color-primary)] text-white rounded-xl text-sm font-bold hover:opacity-80 mt-2">
                新規登録 →
            </button>

        </form>

        <a href="/login" class="flex items-center justify-center gap-2 mt-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-500 hover:opacity-80">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M20 8V14M23 11H17M12.5 7C12.5 9.20914 10.7091 11 8.5 11C6.29086 11 4.5 9.20914 4.5 7C4.5 4.79086 6.29086 3 8.5 3C10.7091 3 12.5 4.79086 12.5 7Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" />
            </svg>
            ログインはこちら
        </a>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>












<!--
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

</html> -->
