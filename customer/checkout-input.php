<?php
session_start();
require '../functions.php';

$user_id = $_SESSION['customer']['user_id'] ?? null;

if (!$user_id) {
    header('Location: login-input.php');
    exit();
}

$pdo = getPDO();

$cart_sql = $pdo->prepare("SELECT * FROM gg_carts WHERE user_id = ?");
$cart_sql->execute([$user_id]);
$cart_item = $cart_sql->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_item) {
    header('Location: cart-input.php');
    exit();
}

// --------------------------------------------------
// (A) 計算ロジック（簡略化 - 画像の値に合わせる）
// --------------------------------------------------
$total_price = 15000; // 商品小計
$shipping_fee = 500;
$grand_total = $total_price + $shipping_fee;

$sql_user = $pdo->prepare("SELECT * FROM gg_users WHERE user_id = ?");
$sql_user->execute([$user_id]);
$user_info = $sql_user->fetch(PDO::FETCH_ASSOC);

// フォームの初期値を画像の値に合わせる
$user_name = h($_SESSION['customer']['firstname'] ?? '山田') . ' ' . h($_SESSION['customer']['lastname'] ?? '太郎');
$user_postcode = h($user_info['postalcode'] ?? '545-0042');
$user_address = h($user_info['address'] ?? '大阪府大阪市阿倍野区丸山通 1-6-3');
$user_tel = h($user_info['phone_number'] ?? '02012345678');

// セッションに合計金額を保存（checkout-confirm.phpで使用）
$_SESSION['order'] = [
    'cart' => $cart_item,
    'total' => $total_price
];
?>
<?php require '../header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG STORE - 配送先情報の入力</title>
    
    <link rel="stylesheet" href="./css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="main-content">
        <main class="page-content">
            
            <h2 class="page-title">配送先情報の入力</h2>

            <form action="checkout-confirm.php" method="POST">
                
                <div class="form-grid-checkout">
                    
                    <div class="card" style="margin-top: 0;">
                        
                        <h3 class="card-title">配送先情報入力</h3>
                        
                        <div class="form-group">
                            <label for="name" class="form-label">お名前 <span class="required-badge">必須</span></label>
                            <input type="text" id="name" name="name" class="form-input" value="<?php echo $user_name; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="postcode" class="form-label">郵便番号 <span class="required-badge">必須</span></label>
                            <input type="text" id="postcode" name="postcode" class="form-input" value="<?php echo $user_postcode; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">住所 <span class="required-badge">必須</span></label>
                            <input type="text" id="address" name="address" class="form-input" value="<?php echo $user_address; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="tel" class="form-label">電話番号 <span class="required-badge">必須</span></label>
                            <input type="text" id="tel" name="tel" class="form-input" value="<?php echo $user_tel; ?>" required>
                        </div>
                    </div>
                    
                    <div class="order-summary-box">
                        <h3 class="card-title">ご注文概要</h3>
                        
                        <div class="order-summary-line">
                            <span>商品小計:</span>
                            <span>¥<?php echo number_format($total_price); ?></span>
                        </div>
                        <div class="order-summary-line">
                            <span>送料:</span>
                            <span>¥<?php echo number_format($shipping_fee); ?></span>
                        </div>
                        
                        <div class="order-summary-total">
                            <span>合計金額:</span>
                            <span>¥<?php echo number_format($grand_total); ?></span>
                        </div>

                        <p class="text-xs text-gray-500 mt-4">
                            次へ進むと、入力内容の最終確認画面が表示されます。
                        </p>
                    </div>
                </div>

                <div class="card" style="margin-top: 0;">
                    <button type="submit" class="button button-primary">
                        <i class="fas fa-chevron-circle-right"></i> 確認画面へ進む
                    </button>
                    <a href="cart-input.php" class="button button-primary" style="display: block; margin-top: 1rem; background-color: #282828; color: var(--accent-blue); border: 1px solid var(--accent-blue);">
                        <i class="fas fa-arrow-left"></i> カートに戻る
                    </a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>