<?php
// Require で商品detailページの下に張り付けるときは（1st section) の部分は消してください。
//　product_idとかの変数はdetailページからとります。
// 1st section
require "../functions.php";

session_start();

$pdo = null;
$pdo = getPDO();

$product_id = 2; // $_POST['product_id']
$product_name = "Cyberpunk 2077";   // $_POST['product_name']
$product_id_type = "game_id"; // $_POST['product_type']

// ここまで1st section

if(!isset($product_id) && !in_array($product_id_type,["game_id,gadget_id"])){
    die("エラーが発生しました。");
}else{
    $sql = "SELECT reviews.review_id,reviews.rating,reviews.game_id,reviews.gadget_id,reviews.comment,reviews.review_date,users.firstname,users.lastname
            FROM gg_reviews as reviews 
            INNER JOIN gg_users as users 
            on reviews.user_id = users.user_id WHERE ".$product_id_type." = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([h($product_id)]);
    $reviews = $stmt->fetchAll();
}

?>




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
        <div class="container-shadow bg-[#242424] p-6 rounded-2xl">
            <h2 class="text-2xl font-semibold text-gray-100 mb-4 border-b border-gray-700 pb-2"><?php echo $product_name; ?> のレビュー</h2>
            <div id="reviews-list">
                
                <?php if (empty($reviews)): // レビューが0件の場合 ?>
                    <p class="text-center text-gray-500 py-4">まだレビューがありません。最初のレビューを投稿しましょう！</p>
                
                <?php else: // レビューが1件以上ある場合 ?>
                    <?php foreach ($reviews as $review): // PHPのループでレビューを描画 ?>
                        <?php
                        $review_poster = $review['firstname']." ".$review['lastname']."さま";
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


                            <p class="text-xs text-cyan-400 mb-1 font-medium">
                                投稿者: <?php echo h($review_poster); ?>
                            </p>
                            <!--  $review['gadget_id'] : $review['game_id']) -->
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-100"><?php echo h($product_name) ?></h3> 
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