<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG store | レビュー特典シミュレーション</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        


        /* 2. シャドウをダークモードに適した、控えめな光に変更 */
        .container-shadow {
            /* 濃い背景色に合わせた影 */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4), 0 1px 5px rgba(0, 0, 0, 0.2);
        }
        
        .review-card {
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }
        .review-card:hover {
            transform: translateY(-3px);
            /* ホバーでシャドウを強調し、シアンのアクセントを追加 */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
            border-color: #22d3ee; /* cyan-400 */
        }
        .rating-star {
            color: #FFC700; /* 星の色はコントラストのため維持 */
        }
    </style>
    <!-- Firebase SDK Imports (Module setup is essential) -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, addDoc, onSnapshot, collection, query, serverTimestamp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";
        import { setLogLevel } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";
        
        // --- 1. 定数と初期設定 ---
        setLogLevel('debug');
        
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : null;
        const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;

        // UI要素
        const loadingElement = document.getElementById('loading-overlay');
        const userIdDisplay = document.getElementById('user-id-display');
        const reviewsList = document.getElementById('reviews-list');
        const reviewForm = document.getElementById('review-form');

        /**
         * Firebaseと認証を初期化し、レビューシステムを起動する
         */
        async function initializeAppAndAuth() {
            if (!firebaseConfig) {
                console.error("Firebase config is missing. Cannot initialize Firebase.");
                userIdDisplay.textContent = 'エラー: Firebase設定がありません。';
                loadingElement.classList.add('hidden');
                return;
            }

            const app = initializeApp(firebaseConfig);
            const db = getFirestore(app);
            const auth = getAuth(app);
            
            // 認証状態の監視
            onAuthStateChanged(auth, async (user) => {
                let currentUserId = null;
                
                if (user) {
                    currentUserId = user.uid;
                    userIdDisplay.textContent = `ユーザーID: ${currentUserId}`;
                } else {
                    try {
                        if (initialAuthToken) {
                            await signInWithCustomToken(auth, initialAuthToken);
                            return; 
                        } else {
                            const anonymousUser = await signInAnonymously(auth);
                            currentUserId = anonymousUser.user.uid;
                            userIdDisplay.textContent = `（匿名）ユーザーID: ${currentUserId}`;
                        }
                    } catch (error) {
                        console.error("Authentication failed, falling back to random ID:", error);
                        currentUserId = crypto.randomUUID();
                        userIdDisplay.textContent = `（認証失敗/匿名）ユーザーID: ${currentUserId}`;
                    }
                }

                loadingElement.classList.add('hidden'); 
                
                if (currentUserId) {
                    initializeReviewSystem(currentUserId, appId, db);
                } else {
                    console.error("Critical: User ID could not be determined.");
                }
            });
        }

        /**
         * レビューの読み取り、書き込み、特典計算をセットアップする
         * @param {string} currentUserId - 現在のユーザーID
         * @param {string} currentAppId - アプリケーションID
         * @param {object} firestoreDb - Firestoreインスタンス
         */
        function initializeReviewSystem(currentUserId, currentAppId, firestoreDb) {
            const reviewsCollectionPath = `artifacts/${currentAppId}/users/${currentUserId}/reviews`;
            const reviewsCollectionRef = collection(firestoreDb, reviewsCollectionPath);

            const q = query(reviewsCollectionRef);
            
            onSnapshot(q, (snapshot) => {
                let reviews = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));

                // タイムスタンプでソート (降順) - サーバー側でのorderByを避けるため
                reviews.sort((a, b) => (b.timestamp?.seconds || 0) - (a.timestamp?.seconds || 0));

                renderReviews(reviews, currentUserId);
                updateDiscountStatus(reviews.length);
            }, (error) => {
                console.error("Error listening to reviews (Firestore connection error):", error);
                reviewsList.innerHTML = `<p class="text-center text-red-400 py-4">データ取得中にエラーが発生しました。</p>`;
            });

            // レビュー投稿処理
            if (reviewForm) {
                reviewForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const gameTitle = document.getElementById('game-title').value;
                    const rating = parseInt(document.getElementById('rating').value, 10);
                    const comment = document.getElementById('comment').value;

                    if (!gameTitle || !comment || isNaN(rating) || rating < 1 || rating > 5) {
                        showCustomMessage('全てのフィールドを正しく入力してください。', 'error');
                        return;
                    }

                    const submitButton = reviewForm.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.textContent = '投稿中...';
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');

                    try {
                        await addDoc(reviewsCollectionRef, {
                            userId: currentUserId,
                            gameTitle: gameTitle,
                            rating: rating,
                            comment: comment,
                            timestamp: serverTimestamp(),
                        });

                        reviewForm.reset();
                        showCustomMessage('レビューが正常に投稿されました！', 'success');

                    } catch (error) {
                        console.error("Error adding document:", error);
                        showCustomMessage('レビューの投稿中にエラーが発生しました。', 'error');
                    } finally {
                        submitButton.disabled = false;
                        submitButton.textContent = 'レビューを投稿';
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }
        }

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

        /**
         * レビューリストをレンダリングする
         * @param {Array<object>} reviews - レビューデータの配列
         */
        function renderReviews(reviews) {
            reviewsList.innerHTML = '';
            
            if (reviews.length === 0) {
                reviewsList.innerHTML = `<p class="text-center text-gray-500 py-4">まだレビューがありません。最初のレビューを投稿しましょう！</p>`;
                return;
            }

            reviews.forEach(review => {
                const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                const date = review.timestamp ? new Date(review.timestamp.toDate()).toLocaleString('ja-JP', {
                    year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'
                }) : '保存中...';
                
                const item = document.createElement('div');
                // bg-[#242424] (ダークカード背景)
                item.className = 'review-card bg-[#242424] p-6 rounded-xl border border-gray-700 mb-4';
                item.innerHTML = `
                    <p class="text-xs text-cyan-400 mb-1 font-medium">
                        投稿者: ${review.userId}
                    </p>
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-xl font-bold text-gray-100">${review.gameTitle}</h3>
                        <div class="flex flex-shrink-0 items-center">
                            <span class="rating-star text-2xl mr-1">${stars}</span>
                            <span class="text-sm text-gray-400 font-semibold">(${review.rating}/5)</span>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed italic border-l-2 border-gray-600 pl-3">${review.comment}</p>
                    <p class="text-xs text-gray-500 mt-3 text-right">
                        投稿日時: ${date}
                    </p>
                `;
                reviewsList.appendChild(item);
            });
        }

        /**
         * 割引ステータスを更新する
         * @param {number} reviewCount - 総レビュー数
         */
        function updateDiscountStatus(reviewCount) {
            const MAX_DISCOUNT = 10.0;
            const INCREASE_PER_REVIEW = 0.05;

            let currentDiscount = Math.min(reviewCount * INCREASE_PER_REVIEW, MAX_DISCOUNT);
            
            // UIの更新
            document.getElementById('review-count-display').textContent = reviewCount;
            document.getElementById('current-discount-display').textContent = currentDiscount.toFixed(2) + '%';
            
            const nextReviewMessage = document.getElementById('next-review-message');
            if (currentDiscount < MAX_DISCOUNT) {
                const nextDiscountValue = (currentDiscount + INCREASE_PER_REVIEW).toFixed(2);
                const neededReviews = Math.ceil((MAX_DISCOUNT - currentDiscount) / INCREASE_PER_REVIEW);
                
                nextReviewMessage.innerHTML = `
                    <span class="text-cyan-400 font-extrabold">次の割引 (${nextDiscountValue}%)</span> まで **1レビュー**！<br>
                    最大割引率 **${MAX_DISCOUNT.toFixed(0)}%** まであと **${neededReviews}レビュー**です。
                `;
            } else {
                nextReviewMessage.innerHTML = `
                    <span class="text-green-400 font-extrabold">おめでとうございます！</span><br>
                    最大割引率 **${MAX_DISCOUNT.toFixed(0)}%** に到達しました。
                `;
            }
        }

        // --- 起動 ---
        initializeAppAndAuth(); 
    </script>
</head>
<body class="p-0">

    <!-- ローディングオーバーレイ -->
    <div id="loading-overlay" class="fixed inset-0 bg-[#1a1a1a] z-50 flex flex-col items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-cyan-400"></div>
        <p class="mt-4 text-cyan-400 font-semibold">GG storeに接続中...</p>
    </div>

    <!-- メインコンテナ (全体にパディングを追加) -->
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        


        <!-- メッセージコンテナ (カスタムアラート用) -->
        <div id="custom-message-container"></div>

        <!-- 特典ステータスカード -->
        <div class="container-shadow bg-[#242424] p-6 rounded-2xl mb-8 border-t-8 border-cyan-400">
            <h2 class="text-xl font-bold text-gray-100 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.974 2.887a1 1 0 00-.362 1.114l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.974-2.887a1 1 0 00-1.176 0l-3.974 2.887c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.362-1.114L2.016 9.092c-.783-.57-.381-1.81.588-1.81h4.915a1 1 0 00.95-.69l1.519-4.674z" />
                </svg>
                あなたの割引レベル
            </h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                
                <!-- 投稿レビュー数 -->
                <div class="p-4 bg-[#333333] rounded-lg border-cyan-400 border">
                    <p class="text-sm text-gray-300 font-medium">総投稿レビュー数</p>
                    <p class="text-4xl font-extrabold text-cyan-400 mt-1" id="review-count-display">0</p>
                    <span class="text-sm text-gray-400">件</span>
                </div>

                <!-- 現在の割引率 -->
                <div class="p-4 bg-[#333333] rounded-lg border-green-400 border">
                    <p class="text-sm text-gray-300 font-medium">現在の割引率</p>
                    <p class="text-4xl font-extrabold text-green-400 mt-1" id="current-discount-display">0.00%</p>
                    <span class="text-sm text-gray-400">OFF</span>
                </div>

                <!-- 次の特典 -->
                <div class="p-4 bg-[#333333] rounded-lg border-yellow-400 border col-span-1 sm:col-span-1 flex flex-col justify-center">
                    <p class="text-sm text-gray-300 font-medium mb-1">達成目標</p>
                    <p class="text-base font-semibold text-yellow-400" id="next-review-message">
                        次の割引率上昇まであと **1レビュー**！
                    </p>
                </div>
            </div>
            
            <p class="text-xs text-gray-500 mt-4 text-right">
                割引率: レビュー投稿ごとに0.05%上昇 (最大10.00%)
            </p>
        </div>

        <!-- レビュー投稿フォーム -->
        <div class="container-shadow bg-[#242424] p-6 rounded-2xl mb-8">
            <h2 class="text-2xl font-semibold text-gray-100 mb-4 border-b border-gray-700 pb-2">レビューを投稿する</h2>
            
            <form id="review-form">
                
                <!-- ゲームタイトル -->
                <div class="mb-4">
                    <label for="game-title" class="block text-sm font-bold text-gray-300">ゲーム/ガジェット名 <span class="text-red-400">*</span></label>
                    <input type="text" id="game-title" required class="mt-1 block w-full rounded-lg bg-[#333333] text-gray-100 border-gray-600 shadow-sm p-3 border focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/50 transition">
                </div>

                <!-- 評価 -->
                <div class="mb-4">
                    <label for="rating" class="block text-sm font-bold text-gray-300">評価 (1〜5) <span class="text-red-400">*</span></label>
                    <select id="rating" required class="mt-1 block w-full rounded-lg bg-[#333333] text-gray-100 border-gray-600 shadow-sm p-3 border focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/50 transition">
                        <option value="" disabled selected class="bg-gray-700 text-gray-400">評価を選択してください</option>
                        <option value="5" class="bg-[#333333]">★★★★★ (5 - 最高)</option>
                        <option value="4" class="bg-[#333333]">★★★★☆ (4 - 良い)</option>
                        <option value="3" class="bg-[#333333]">★★★☆☆ (3 - 普通)</option>
                        <option value="2" class="bg-[#333333]">★★☆☆☆ (2 - 悪い)</option>
                        <option value="1" class="bg-[#333333]">★☆☆☆☆ (1 - 最低)</option>
                    </select>
                </div>

                <!-- コメント -->
                <div class="mb-6">
                    <label for="comment" class="block text-sm font-bold text-gray-300">コメント <span class="text-red-400">*</span></label>
                    <textarea id="comment" rows="4" required class="mt-1 block w-full rounded-lg bg-[#333333] text-gray-100 border-gray-600 shadow-sm p-3 border focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/50 transition" placeholder="正直な感想をお聞かせください"></textarea>
                </div>
                
                <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-lg text-lg font-bold text-white bg-cyan-600 hover:bg-cyan-500 active:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-400/50 transition duration-150 ease-in-out transform hover:scale-[1.005]">
                    レビューを投稿
                </button>
            </form>
        </div>

        <!-- レビューリスト -->
        <div class="container-shadow bg-[#242424] p-6 rounded-2xl">
            <h2 class="text-2xl font-semibold text-gray-100 mb-4 border-b border-gray-700 pb-2">あなたの投稿レビュー履歴</h2>
            <div id="reviews-list">
                <!-- レビューはJavaScriptによってここに挿入されます -->
                <p class="text-center text-gray-500 py-4">読み込み中...</p>
            </div>
        </div>
    </div>
</body>
</html>
