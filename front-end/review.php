<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/review.css">
    <title>GG store | レビュー特典シミュレーション</title>
    
    <script type="module" src="./js/review.js"></script>

</head>
<body>

    <div id="loading-overlay" class="loader">
        <div class="loader-spin"></div>
        <p class="loader-txt">GG storeに接続中...</p>
    </div>

    <div class="main-ctn">
        
        <div id="custom-message-container"></div>

        <div class="card container-shadow bdr-top">
            <h2 class="card-ttl">
                <svg xmlns="http://www.w3.org/2000/svg" class="ttl-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.974 2.887a1 1 0 00-.362 1.114l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.974-2.887a1 1 0 00-1.176 0l-3.974 2.887c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.362-1.114L2.016 9.092c-.783-.57-.381-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z" />
                </svg>
                あなたの割引レベル
            </h2>
            
            <div class="stat-grid">
                
                <div class="stat-box bdr-cyan">
                    <p class="stat-sub">総投稿レビュー数</p>
                    <p class="stat-num txt-cyan" id="review-count-display">0</p>
                    <span class="stat-unit">件</span>
                </div>

                <div class="stat-box bdr-green">
                    <p class="stat-sub">現在の割引率</p>
                    <p class="stat-num txt-green" id="current-discount-display">0.00%</p>
                    <span class="stat-unit">OFF</span>
                </div>

                <div class="stat-box bdr-yellow stat-goal">
                    <p class="stat-sub">達成目標</p>
                    <p class="stat-goal-txt" id="next-review-message">
                        次の割引率上昇まであと **1レビュー**！
                    </p>
                </div>
            </div>
            
            <p class="card-foot">
                割引率: レビュー投稿ごとに0.05%上昇 (最大10.00%)
            </p>
        </div>

        <div class="card container-shadow">
            <h2 class="form-ttl">レビューを投稿する</h2>
            
            <form id="review-form">
                
                <div class="form-group">
                    <label for="game-title" class="form-label">ゲーム/ガジェット名 <span class="req">*</span></label>
                    <input type="text" id="game-title" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="rating" class="form-label">評価 (1〜5) <span class="req">*</span></label>
                    <select id="rating" required class="form-input">
                        <option value="" disabled selected>評価を選択してください</option>
                        <option value="5">★★★★★ (5 - 最高)</option>
                        <option value="4">★★★★☆ (4 - 良い)</option>
                        <option value="3">★★★☆☆ (3 - 普通)</option>
                        <option value="2">★★☆☆☆ (2 - 悪い)</option>
                        <option value="1">★☆☆☆☆ (1 - 最低)</option>
                    </select>
                </div>

                <div class="form-group-last">
                    <label for="comment" class="form-label">コメント <span class="req">*</span></label>
                    <textarea id="comment" rows="4" required class="form-input" placeholder="正直な感想をお聞かせください"></textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    レビューを投稿
                </button>
            </form>
        </div>

        <div class="card container-shadow">
            <h2 class="form-ttl">あなたの投稿レビュー履歴</h2>
            <div id="reviews-list">
                <p class="empty-msg">読み込み中...</p>
            </div>
        </div>
    </div>
</body>
</html>