<?php require "../functions.php" ?>

<?php
// 1. セッション管理の開始 
// 必ずファイルの先頭に記述してください
session_start();

require "../header.php";
// --- データベース接続設定 ---

$pdo = null;
$message = null; // ユーザーへの通知（投稿成功など）
$maxDiscount = 10;
$discountRate = 0.05;

try {
    // データベースに接続
    $pdo = getPDO();

} catch (PDOException $e) {
    // 接続失敗時はエラーメッセージを表示して終了
    die("データベース接続に失敗しました: " . $e->getMessage());
}

// 2. ユーザーIDの確認（セッションに無ければ新規作成）
if (empty($_SESSION['customer'])) {
    // 簡易的な匿名IDをセッションに保存
    $_SESSION['customer']['id'] = 'user_' . bin2hex(random_bytes(16));
}
//$_SESSION['customer']['id']
$currentUserId = $_SESSION['customer']['user_id'];
$currentGameId = 2;

// (B) 割引ステータスを計算
try{
    $sql = $pdo->prepare("SELECT * FROM gg_premium WHERE user_id = ? LIMIT 1");
    $sql -> execute([$currentUserId]);
    $premiums = $sql->fetchAll();
}catch (PDOException $e){
    $_SESSION['message'] = "エラーが発生しました。やり直してください。";
}


// premium からuser_idのチェック。 
$is_active = isActiveMember($pdo, $currentUserId); 
if($is_active){
    echo "yes";
}else{
    echo "no";
}

if(hasBought($pdo,$currentUserId,$currentGameId,"game_id")){
    echo "yes1";
}else{
    echo "no1";
}

// 3. POSTリクエストの処理 (フォームが送信された場合)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // サーバーサイドでの入力値バリデーション
    $action = $_POST['action'] ?? 'submit'; // デフォルトは 'submit'

    if ($action === 'submit') {
        $rating = filter_var($_POST['rating'] ?? 0, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating !== false && !empty($comment)) {
            // バリデーション成功
            try {
                $sql = "INSERT INTO gg_reviews (user_id, rating, game_id, comment) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$currentUserId,$rating,$currentGameId,$comment]);

                if($is_active && $premiums[0]['current_discount'] < 10.00){
                    if(hasBought($pdo,$currentUserId,$currentGameId,"game_id")){
                        $current_Discount = $premiums[0]['new_discount'];
                        $new_Discount = $premiums[0]['new_discount'] + $discountRate;
                        $sql = $pdo->prepare("UPDATE gg_premium SET current_discount = ?,new_discount = ? where user_id = ?");
                        $sql->execute([$current_Discount,$new_Discount,$currentUserId]);
                    }
                }

                // 成功メッセージをセッションに保存
                $_SESSION['message'] = ['text' => 'レビューが正常に投稿されました！', 'type' => 'success'];

            } catch (PDOException $e) {
                $_SESSION['message'] = ['text' => 'レビューの投稿中にエラーが発生しました。', 'type' => 'error'];
            }
        } else {
            // バリデーション失敗
            $_SESSION['message'] = ['text' => '全てのフィールドを正しく入力してください。', 'type' => 'error'];
        }
    }elseif($action === 'delete') {
        // --- ★新規: レビュー削除処理 ---
        $reviewIdToDelete = filter_var($_POST['review_id'] ?? 0, FILTER_VALIDATE_INT);
        
        if ($reviewIdToDelete > 0) {
            try {
                // 買った商品のれーびゅだったら割引を減らす　
                $sql = $pdo->prepare("SELECT game_id FROM gg_reviews WHERE review_id = ?");
                $sql->execute([$reviewIdToDelete]);
                $deletedId = $sql->fetchAll();
                if($deletedId[0]['game_id']==null){
                    $deletedProductId = $deletedId[0]['gadget_id'];
                }else{
                    $deletedProductId = $deletedId[0]['game_id'];
                }
                if(hasBought($pdo,$currentUserId,$deletedProductId,"gadget_id")){
                    $current_Discount = $premiums[0]['current_discount']-$discountRate;
                    $new_Discount = $premiums[0]['current_discount'];
                    $sql = $pdo->prepare("UPDATE gg_premium SET current_discount = ?,new_discount = ? where user_id = ?");
                    $sql->execute([$current_Discount,$new_Discount,$currentUserId]);
                }

                // 必ず自分のレビューであること(userId)を確認してから削除する
                $sql = "DELETE FROM gg_reviews WHERE review_id = ? AND user_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$reviewIdToDelete, $currentUserId]);

                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = ['text' => 'レビューを削除しました。', 'type' => 'success'];
                } else {
                    // 削除されなかった場合 (他人のレビューIDを指定された等)
                    $_SESSION['message'] = ['text' => '削除に失敗しました（該当のレビューが見つからないか、権限がありません）。', 'type' => 'error'];
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = ['text' => '削除中にデータベースエラーが発生しました。', 'type' => 'error'];
            }
        } else {
            $_SESSION['message'] = ['text' => '無効なリクエストです。', 'type' => 'error'];
        }
    }

    // Post/Redirect/Get (PRG) パターン：二重投稿を防止
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// フォーム送信後のメッセージがあれば取得し、セッションから削除
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// (A) 該当ユーザーのレビューをすべて取得

$sql = $pdo->prepare("SELECT review.review_id,review.user_id,game.game_name,gadget.gadget_name,review.rating,review.comment,review.review_date
                        FROM gg_reviews as review
                        LEFT JOIN gg_game as game on review.game_id = game.game_id 
                        LEFT JOIN gg_gadget as gadget on review.gadget_id = gadget.gadget_id
                        WHERE review.user_id = ? ORDER BY review_date DESC");
$sql->execute([$currentUserId]);
$reviews = $sql->fetchAll();



$reviewCount = count($reviews);
$currentReviewTitle = "hidden";

// (C) 次の特典メッセージを生成
$nextReviewMessageHtml = '';
if(!empty($premiums)){
    $currentDiscount = $premiums[0]['new_discount'];
    if($premiums[0]['is_active']){
        $currentReviewTitle = '';
        if ($currentDiscount < 10) {
            $nextDiscountValue = number_format($currentDiscount + $discountRate, 2);
            $neededReviews = ceil(($maxDiscount - $currentDiscount) / $discountRate);
        
            $nextReviewMessageHtml = '
                <span class="text-cyan-400 font-extrabold">次の割引 (' . $nextDiscountValue . '%)</span> まで **1レビュー**！<br>
                最大割引率 **' . number_format($maxDiscount, 0) . '%** まであと **' . $neededReviews . 'レビュー**です。
            ';
        } else {
            $nextReviewMessageHtml = '
                <span class="text-green-400 font-extrabold">おめでとうございます！</span><br>
                最大割引率 **' . number_format($maxDiscount, 0) . '%** に到達しました。
            ';
        }
    }
}

//E 今処理しているusernameと処理されているgameかgadgetの名前を取り出す。
$sql = $pdo->prepare("SELECT game.game_name FROM gg_game AS game WHERE game.game_id = ? LIMIT 1");
$sql ->execute([$currentGameId]);
$currentProduct = $sql->fetchAll();
$currentUserName = $_SESSION['customer']['firstname'] . " " . $_SESSION['customer']['lastname'];
$currentProductName = $currentProduct[0]['game_name'];



// これ以降はHTMLの描画
?>

<?php require "myreview.php"; ?>


