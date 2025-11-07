<?php require "../functions.php" ?>
<?php
// 1. セッション管理の開始 
// 必ずファイルの先頭に記述してください
session_start();

// --- データベース接続設定 ---

$pdo = null;
$message = null; // ユーザーへの通知（投稿成功など）

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
$currentUserId = 3;
$currentGameId = 1;

// (B) 割引ステータスを計算
try{
    $sql = $pdo->prepare("SELECT * FROM gg_premium WHERE user_id = ?");
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

// 3. POSTリクエストの処理 (フォームが送信された場合)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // サーバーサイドでの入力値バリデーション
    
    $rating = filter_var($_POST['rating'] ?? 0, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating !== false && !empty($comment)) {
        // バリデーション成功
        try {
            $sql = "INSERT INTO gg_reviews (user_id, rating, game_id, comment) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentUserId,$rating,$currentGameId,$comment]);

            // 成功メッセージをセッションに保存
            $_SESSION['message'] = ['text' => 'レビューが正常に投稿されました！', 'type' => 'success'];

        } catch (PDOException $e) {
            $_SESSION['message'] = ['text' => 'レビューの投稿中にエラーが発生しました。', 'type' => 'error'];
        }
    } else {
        // バリデーション失敗
        $_SESSION['message'] = ['text' => '全てのフィールドを正しく入力してください。', 'type' => 'error'];
    }

    // Post/Redirect/Get (PRG) パターン：二重投稿を防止
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 4. GETリクエストの処理 (ページの表示)

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

$maxDiscount = 10;
$discountRate = 0.05;
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
$sql = $pdo->prepare("SELECT user.firstname,user.lastname,game.game_name FROM gg_users AS user, gg_game AS game WHERE user.user_id = ? and game.game_id = ? LIMIT 1");
$sql ->execute([$currentUserId,$currentGameId]);
$currentProduct = $sql->fetchAll();
$currentUserName = $currentProduct[0]['firstname'] . " " . $currentProduct[0]['lastname'];
$currentProductName = $currentProduct[0]['game_name'];



// これ以降はHTMLの描画
?>

<?php require "review.php"; ?>


