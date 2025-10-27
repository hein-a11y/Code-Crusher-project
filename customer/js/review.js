// JavaScriptロジックは変更ありません
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
const reviewsList = document.getElementById('reviews-list');
const reviewForm = document.getElementById('review-form');

/**
 * Firebaseと認証を初期化し、レビューシステムを起動する
 */
async function initializeAppAndAuth() {
    if (!firebaseConfig) {
        console.error("Firebase config is missing. Cannot initialize Firebase.");
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
            console.log("User authenticated with UID:", currentUserId);
        } else {
            try {
                if (initialAuthToken) {
                    await signInWithCustomToken(auth, initialAuthToken);
                    return; 
                } else {
                    const anonymousUser = await signInAnonymously(auth);
                    currentUserId = anonymousUser.user.uid;
                    console.log("Anonymous user authenticated with UID:", currentUserId);
                }
            } catch (error) {
                console.error("Authentication failed, falling back to random ID:", error);
                currentUserId = crypto.randomUUID();
                console.log("Fallback user ID:", currentUserId);
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

        reviews.sort((a, b) => (b.timestamp?.seconds || 0) - (a.timestamp?.seconds || 0));

        renderReviews(reviews, currentUserId);
        updateDiscountStatus(reviews.length);
    }, (error) => {
        console.error("Error listening to reviews (Firestore connection error):", error);
        reviewsList.innerHTML = `<p class="error-msg">データ取得中にエラーが発生しました。</p>`;
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
            submitButton.classList.add('disabled');

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
                submitButton.classList.remove('disabled');
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

    const baseClass = 'msg-box';
    const typeClass = type === 'success' ? 'msg-ok' : 'msg-err';
    const html = `<div class="${baseClass} ${typeClass}">${message}</div>`;
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
        reviewsList.innerHTML = `<p class="empty-msg">まだレビューがありません。最初のレビューを投稿しましょう！</p>`;
        return;
    }

    reviews.forEach(review => {
        const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
        const date = review.timestamp ? new Date(review.timestamp.toDate()).toLocaleString('ja-JP', {
            year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'
        }) : '保存中...';
        
        const item = document.createElement('div');
        item.className = 'review-card';
        item.innerHTML = `
            <p class="rev-uid">${review.userId}</p>
            <div class="rev-head">
                <h3 class="rev-title">${review.gameTitle}</h3>
                <div class="rev-rate">
                    <span class="rating-star">${stars}</span>
                    <span class="rev-rate-num">(${review.rating}/5)</span>
                </div>
            </div>
            <p class="rev-com">${review.comment}</p>
            <p class="rev-date">${date}</p>
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
    
    document.getElementById('review-count-display').textContent = reviewCount;
    document.getElementById('current-discount-display').textContent = currentDiscount.toFixed(2) + '%';
    
    const nextReviewMessage = document.getElementById('next-review-message');
    if (currentDiscount < MAX_DISCOUNT) {
        const nextDiscountValue = (currentDiscount + INCREASE_PER_REVIEW).toFixed(2);
        const neededReviews = Math.ceil((MAX_DISCOUNT - currentDiscount) / INCREASE_PER_REVIEW);
        
        nextReviewMessage.innerHTML = `
            <span class="accent-txt">次の割引 (${nextDiscountValue}%)</span> まで **1レビュー**！<br>
            最大割引率 **${MAX_DISCOUNT.toFixed(0)}%** まであと **${neededReviews}レビュー**です。
        `;
    } else {
        nextReviewMessage.innerHTML = `
            <span class="success-txt">おめでとうございます！</span><br>
            最大割引率 **${MAX_DISCOUNT.toFixed(0)}%** に到達しました。
        `;
    }
}

// --- 起動 ---
initializeAppAndAuth();