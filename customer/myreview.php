<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG store | レビュー特典シミュレーション (PHP版)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        :root {
            --primary-color: #00bfff; /* Neon blue accent */
            --background-color: #121212; /* Deep black */
            --surface-color: #1e1e1e;  /* Slightly lighter for cards/headers */
            --text-color: #e0e0e0;      /* Light grey for readability */
            --text-secondary-color: #a0a0a0; /* Dimmer text */
        }

        /* 1. 全体の背景をダークテーマに変更 */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a1a1a; /* ディープチャコール */
            min-height: 100vh;
            padding: 0;
            color: #e5e7eb; /* デフォルトテキストを明るく */
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);   
            text-decoration: none;
        }

        /* 2. シャドウをダークモードに適した、控えめな光に変更 */
        .container-shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4), 0 1px 5px rgba(0, 0, 0, 0.2);
        }
        
        .review-card {
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }
        .review-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            border-color: #22d3ee; /* cyan-400 */
        }
        .rating-star {
            color: #FFC700; /* 星の色はコントラストのため維持 */
        }
    </style>
    </head>
<body class="p-0">

    <div id="loading-overlay" class="fixed inset-0 bg-[#1a1a1a] z-50 flex flex-col items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-cyan-400"></div>
        <p class="mt-4 text-cyan-400 font-semibold">GG storeに接続中...</p>
    </div>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <header class="mb-8 p-4 bg-[#242424] rounded-xl container-shadow z-0">
            <div class="flex items-center justify-between">
                <div class="logo">
                    GG STORE
                </div>
                <p class="text-base text-gray-400 font-medium hidden sm:block">
                    レビュー特典シミュレーション
                </p>
            </div>
            <p id="user-id-display" class="mt-2 text-xs text-gray-500 truncate text-right">
                ユーザー名: <?php echo htmlspecialchars($currentUserName); ?>様
            </p>
        </header>
        
        <div class="<?php echo $currentReviewTitle ?>">

            <div class="container-shadow bg-[#242424] p-6 rounded-2xl mb-8 border-t-8 border-cyan-400">
                <h2 class="text-xl font-bold text-gray-100 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.974 2.887a1 1 0 00-.362 1.114l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.974-2.887a1 1 0 00-1.176 0l-3.974 2.887c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.362-1.114L2.016 9.092c-.783-.57-.381-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z" />
                    </svg>
                    あなたの割引レベル
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                    
                    <div class="p-4 bg-[#333333] rounded-lg border-cyan-400 border">
                        <p class="text-sm text-gray-300 font-medium">総投稿レビュー数</p>
                        <p class="text-4xl font-extrabold text-cyan-400 mt-1" id="review-count-display">
                            <?php echo $reviewCount; ?>
                        </p>
                        <span class="text-sm text-gray-400">件</span>
                    </div>

                    <div class="p-4 bg-[#333333] rounded-lg border-green-400 border">
                        <p class="text-sm text-gray-300 font-medium">現在の割引率</p>
                        <p class="text-4xl font-extrabold text-green-400 mt-1" id="current-discount-display">
                            <?php echo number_format($currentDiscount, 2); ?>%
                        </p>
                        <span class="text-sm text-gray-400">OFF</span>
                    </div>

                    <div class="p-4 bg-[#333333] rounded-lg border-yellow-400 border col-span-1 sm:col-span-1 flex flex-col justify-center">
                        <p class="text-sm text-gray-300 font-medium mb-1">達成目標</p>
                        <p class="text-base font-semibold text-yellow-400" id="next-review-message">
                            <?php echo $nextReviewMessageHtml; // PHPで生成したHTMLをそのまま出力 ?>
                        </p>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 mt-4 text-right">
                    割引率: レビュー投稿ごとに<?php echo $discountRate; ?>%上昇 (最大<?php echo number_format($maxDiscount, 2); ?>%)
                </p>
            </div>
        </div>

        <div class="container-shadow bg-[#242424] p-6 rounded-2xl mb-8">
            <div id="custom-message-container"></div>
            <h2 class="text-2xl font-semibold text-gray-100 mb-4 border-b border-gray-700 pb-2">レビューを投稿する</h2>
            
            <form id="review-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                
                <div class="mb-4">
                    <label for="game-title" class="block text-sm font-bold text-gray-300">ゲーム/ガジェット名 <span class="text-red-400">*</span></label>
                    <input type="text" id="game-title" name="game-title" value="<?php echo $currentProductName ?>" required class="mt-1 block w-full rounded-lg bg-[#333333] text-gray-100 border-gray-600 shadow-sm p-3 border focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/50 transition" readonly>
                </div>

                <div class="mb-4">
                    <label for="rating" class="block text-sm font-bold text-gray-300">評価 (1〜5) <span class="text-red-400">*</span></label>
                    <select id="rating" name="rating" required class="mt-1 block w-full rounded-lg bg-[#333333] text-gray-100 border-gray-600 shadow-sm p-3 border focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/50 transition">
                        <option value="" disabled selected class="bg-gray-700 text-gray-400">評価を選択してください</option>
                        <option value="5" class="bg-[#333333]">★★★★★ (5 - 最高)</option>
                        <option value="4" class="bg-[#333333]">★★★★☆ (4 - 良い)</option>
                        <option value="3" class="bg-[#333333]">★★★☆☆ (3 - 普通)</option>
                        <option value="2" class="bg-[#333333]">★★☆☆☆ (2 - 悪い)</option>
                        <option value="1" class="bg-[#333333]">★☆☆☆☆ (1 - 最低)</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="comment" class="block text-sm font-bold text-gray-300">コメント <span class="text-red-400">*</span></label>
                    <textarea id="comment" name="comment" rows="4" required class="mt-1 block w-full rounded-lg bg-[#333333] text-gray-100 border-gray-600 shadow-sm p-3 border focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/50 transition" placeholder="正直な感想をお聞かせください"></textarea>
                </div>
                
                <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-lg text-lg font-bold text-white bg-cyan-600 hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-400/50 transition duration-150 ease-in-out transform hover:scale-[1.005]">
                    レビューを投稿
                </button>
            </form>
        </div>

        <div class="container-shadow bg-[#242424] p-6 rounded-2xl">
            <h2 class="text-2xl font-semibold text-gray-100 mb-4 border-b border-gray-700 pb-2">あなたの投稿レビュー履歴</h2>
            <div id="reviews-list">
                
                <?php if (empty($reviews)): // レビューが0件の場合 ?>
                    <p class="text-center text-gray-500 py-4">まだレビューがありません。最初のレビューを投稿しましょう！</p>
                
                <?php else: // レビューが1件以上ある場合 ?>
                    <?php foreach ($reviews as $review): // PHPのループでレビューを描画 ?>
                        <?php
                        // PHP側でスターと日付を整形
                        $stars = str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']);
                        // DateTimeオブジェクトを使って日付をフォーマット
                        try {
                            $date = (new DateTime($review['review_date']))->format('Y/m/d H:i');
                        } catch (Exception $e) {
                            $date = '日付不明';
                        }
                        ?>
                        <div class="review-card relative bg-[#242424] p-6 rounded-xl border border-gray-700 mb-4">
                            
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="absolute top-4 right-4 z-10" onsubmit="return confirm('このレビューを本当に削除しますか？');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                <button type="submit" class="p-2 text-gray-500 hover:text-red-400 transition-colors duration-200" aria-label="レビューを削除">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                            <p class="text-xs text-cyan-400 mb-1 font-medium">
                                投稿者: <?php echo htmlspecialchars($currentUserName); ?>
                            </p>
                            <!--  $review['gadget_id'] : $review['game_id']) -->
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-100"><?php echo htmlspecialchars($review['game_name']==null ? $review['gadget_name'] : $review['game_name']); ?></h3> 
                                <div class="flex flex-shrink-0 items-center">
                                    <span class="rating-star text-2xl mr-1"><?php echo $stars; ?></span>
                                    <span class="text-sm text-gray-400 font-semibold">(<?php echo htmlspecialchars($review['rating']); ?>/5)</span>
                                </div>
                            </div>
                            <p class="text-gray-300 leading-relaxed italic border-l-2 border-gray-600 pl-3"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            <p class="text-xs text-gray-500 mt-3 text-right">
                                投稿日時: <?php echo $date; ?>
                            </p>
                        </div>
                        
                        
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script>
        /**
         * カスタムメッセージ（アラート代替）を表示する
         * @param {string} message - 表示するメッセージ
         * @param {string} type - 'success' または 'error'
         */
        function showCustomMessage(message, type) {
            const messageContainer = document.getElementById('custom-message-container');
            if (!messageContainer) return;

            const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            const html = `
                <div class="${bgColor} text-white p-3 rounded-lg shadow-xl mb-4 transition-opacity duration-300">
                    ${message}
                </div>
            `;
            messageContainer.innerHTML = html;

            setTimeout(() => {
                messageContainer.innerHTML = '';
            }, 3000);
        }

        // ページの読み込みが完了したら、ローディング画面を非表示にする
        document.addEventListener('DOMContentLoaded', () => {
            const loadingElement = document.getElementById('loading-overlay');
            if (loadingElement) {
                loadingElement.classList.add('hidden');
            }

            // PHPから渡されたメッセージがあれば表示する
            <?php if (isset($message)): ?>
                showCustomMessage(
                    <?php echo json_encode($message['text']); ?>, 
                    <?php echo json_encode($message['type']); ?>
                );
            <?php endif; ?>
        });
    </script>
</body>
</html>