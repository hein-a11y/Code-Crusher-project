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
// (A) 商品詳細の準備
// --------------------------------------------------
$game_id = [];
$gadget_id = [];

foreach ($cart_item as $item) {
    if (!empty($item['game_id'])) {
        $game_id[] = $item['game_id'];
    } else if (!empty($item['gadget_id'])) {
        $gadget_id[] = $item['gadget_id'];
    }
}

$products = [];

if (!empty($game_id)) {
    $placeholders = implode(',', array_fill(0, count($game_id), '?'));
    $sql_game = $pdo->prepare("SELECT game_id AS id, game_name AS name, price FROM gg_game WHERE game_id IN ($placeholders)");
    $sql_game->execute($game_id);
    foreach ($sql_game->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $products['game-' . $row['id']] = $row;
    }
}

if (!empty($gadget_id)) {
    $placeholders = implode(',', array_fill(0, count($gadget_id), '?'));
    $sql_gadget = $pdo->prepare("SELECT gadget_id AS id, gadget_name AS name, price FROM gg_gadget WHERE gadget_id IN ($placeholders)");
    $sql_gadget->execute($gadget_id);
    foreach ($sql_gadget->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $products['gadget-' . $row['id']] = $row;
    }
}

// --------------------------------------------------
// (B) 計算ループ
// --------------------------------------------------
$total_price = 0;

foreach ($cart_item as $item) {
    $item_key = '';
    if (!empty($item['game_id'])) {
        $item_key = 'game-' . $item['game_id'];
    } else if (!empty($item['gadget_id'])) {
        $item_key = 'gadget-' . $item['gadget_id'];
    }

    if (isset($products[$item_key])) {
        $product_info = $products[$item_key];
        $subtotal = $product_info['price'] * $item['quantity'];
        $total_price += $subtotal;
    }
}

// --------------------------------------------------
// (C) セッションに保存
// --------------------------------------------------
$_SESSION['order'] = [
    'cart' => $cart_item,
    'total' => $total_price
];

$sql_user = $pdo->prepare("SELECT * FROM gg_users WHERE user_id = ?");
$sql_user->execute([$user_id]);
$user_info = $sql_user->fetch(PDO::FETCH_ASSOC);

require '../header.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>配送先情報の入力</title>
    <link rel="stylesheet" href="./css/checkout.css">
</head>
<body>
<main class="main-content">
    <div class="checkout-wrapper">

        <div class="checkout-main">
            <h1>配送先情報の入力</h1>

            <form action="checkout-confirm.php" method="POST">

                <div class="form-group">
                    <div class="form-label-group">
                        <label>お名前</label>
                        <span class="required-badge">必須</span>
                    </div>
                    <input type="text" name="name" value="<?php echo h($user_info['user_name'] ?? ''); ?>" required placeholder="例：山田 太郎">
                </div>

                <div class="form-group">
                    <div class="form-label-group">
                        <label>郵便番号</label>
                        <span class="required-badge">必須</span>
                    </div>
                    <input type="text" name="postcode" value="<?php echo h($user_info['postcode'] ?? ''); ?>" required placeholder="例：123-4567">
                </div>

                <div class="form-group">
                    <div class="form-label-group">
                        <label>住所</label>
                        <span class="required-badge">必須</span>
                    </div>
                    <input type="text" name="address" value="<?php echo h($user_info['address'] ?? ''); ?>" required placeholder="例：東京都〇〇区〇〇 1-2-3">
                </div>

                <div class="form-group">
                    <div class="form-label-group">
                        <label>電話番号</label>
                        <span class="required-badge">必須</span>
                    </div>
                    <input type="text" name="tel" value="<?php echo h($user_info['tel'] ?? ''); ?>" required placeholder="例：090-1234-5678">
                </div>

                <button type="submit" class="checkout-btn">確認画面へ進む</button>
            </form>
        </div>
        <aside class="checkout-sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">よくあるご質問</div>
                <ul class="sidebar-links">
                    <li><a href="#">お支払いについて</a></li>
                    <li><a href="#">配送について</a></li>
                    <li><a href="#">返品・交換について</a></li>
                </ul>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">ご利用ガイド</div>
                <ul class="sidebar-links">
                    <li><a href="#">ご注文のキャンセルについて</a></li>
                    <li><a href="#">お客様窓口はこちら</a></li>
                </ul>
            </div>
        </aside>
        </div>
</main>
</body>
</html>