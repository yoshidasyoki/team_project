<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規投稿</title>
</head>

<body>
    <h2>新規投稿ページ</h2>
    <!-- 例: ログイン中のユーザー名を出力 -->
    <p>ログインユーザー: <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></p>

    <!-- 作成する記事スペース -->
    <div class="w-full max-w-4xl bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-10 space-y-8">

        <!-- Header Title -->
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
            記事の内容を入力
        </h1>

        <!-- Form Elements Container -->
        <form id="articleForm" class="space-y-8" action="/articles/store" method="POST">

            <!-- Input Group 1: Title -->
            <div class="space-y-2">
                <label for="title" class="block font-bold text-gray-800 text-sm sm:text-base">
                    タイトル <span class="text-red-500 font-normal">*</span>
                </label>
                <div class="relative">
                    <input
                        type="text"
                        id="title"
                        name="title"
                        maxlength="100"
                        placeholder="記事のタイトルを入力してください"
                        class="w-full px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400 text-sm sm:text-base">
                </div>
                <!-- Character Counter -->
                <div class="text-right text-xs text-gray-400 pt-1">
                    <span id="charCount">0</span> / 100
                </div>
            </div>

            <!-- Input Group 2: Category (Tags) -->
            <div class="space-y-3">
                <label class="block font-bold text-gray-800 text-sm sm:text-base">
                    カテゴリ（タグ） <span class="text-red-500 font-normal">*</span>
                </label>
                <p class="text-xs sm:text-sm text-gray-400">
                    記事のカテゴリを選択してください
                </p>

                <!-- Tag Badges -->
                <div class="flex flex-wrap gap-3 pt-1">
                    <!-- Ruby Tag -->
                    <button
                        type="button"
                        data-tag="Ruby"
                        data-tag-id="1"
                        class="tag-btn px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 border border-transparent bg-[#fce8e6] text-[#e53935] hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-red-200 active:scale-95">
                        Ruby
                    </button>

                    <!-- PHP Tag -->
                    <button
                        type="button"
                        data-tag="PHP"
                        class="tag-btn px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 border border-transparent bg-[#e3f2fd] text-[#1e88e5] hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-blue-200 active:scale-95">
                        PHP
                    </button>

                    <!-- JS Tag -->
                    <button
                        type="button"
                        data-tag="JS"
                        class="tag-btn px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 border border-transparent bg-[#fff8e1] text-[#f57f17] hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-amber-200 active:scale-95">
                        JS
                    </button>

                    <!-- Git Tag -->
                    <button
                        type="button"
                        data-tag="Git"
                        class="tag-btn px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 border border-transparent bg-[#e8f5e9] text-[#43a047] hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-green-200 active:scale-95">
                        Git
                    </button>
                </div>
            </div>

            <!-- Input Group 3: Body Content -->
            <div class="space-y-2">
                <label for="content" class="block font-bold text-gray-800 text-sm sm:text-base">
                    本文 <span class="text-red-500 font-normal">*</span>
                </label>
                <textarea
                    id="content"
                    rows="14"
                    name="body"
                    placeholder="記事の内容を入力してください..."
                    class="w-full px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400 text-sm sm:text-base resize-y min-h-[250px]"></textarea>
            </div>

            <!-- Action Buttons Area -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                <!-- Cancel Link / Button ※buttonからaタグに変更-->
                <a
                    href="/"
                    class="font-bold text-gray-800 hover:text-gray-600 text-sm sm:text-base transition-colors duration-200 focus:outline-none px-1 py-2 inline-block">
                    キャンセル
                </a>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="px-8 py-3 bg-[#0d2238] hover:bg-[#183350] text-white font-bold text-sm sm:text-base rounded-2xl shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d2238] active:scale-95">
                    投稿する
                </button>
            </div>

        </form>
    </div>

    <script>
        // タグ選択の切り替え処理
        document.querySelectorAll('.tag-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('ring-4');
                const tagId = this.getAttribute('data-tag-id');
                const container = document.getElementById('selectedTagsContainer');
                const existingInput = container.querySelector(`input[value="${tagId}"]`);

                if (existingInput) {
                    existingInput.remove();
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'tags[]';
                    input.value = tagId;
                    container.appendChild(input);
                }
            });
        });

        // 文字数カウント
        const titleInput = document.getElementById('title');
        const charCount = document.getElementById('charCount');
        if (titleInput && charCount) {
            titleInput.addEventListener('input', () => {
                charCount.textContent = titleInput.value.length;
            });
        }
    </script>

</body>

</html>