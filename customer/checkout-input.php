<?php
    session_start();
    require '../functions.php';

?>
<?php

    $user_id = $_SESSION['customer']['user_id'] ?? null;

if (!$user_id) {
    header('Location: login-input.php'); // ログインしてなければ追い出す
    exit();
}

$pdo = getPDO();

$cart_sql = $pdo->prepare("SELECT * FROM gg_carts WHERE user_id = ?");
$cart_sql->execute([$user_id]);
$cart_item = $cart_sql->fetchAll();

if (!$cart_item) {
    header('Location: login-input.php'); 
    exit();
}


// --------------------------------------------------
// (A) まず、$products（商品詳細）の準備（これも必要！）
// --------------------------------------------------

$game_id = [];
$gadget_id = [];

// IDを振り分ける
foreach ($cart_item as $item) {
    if (!empty($item['game_id'])) {
        $game_id[] = $item['game_id'];
    } else if (!empty($item['gadget_id'])) {
        $gadget_id[] = $item['gadget_id'];
    }
}

$products = []; // 商品情報をまとめる配列

// ゲーム情報を取得
if (!empty($game_id)) {
    $placeholders = implode(',', array_fill(0, count($game_id), '?'));
    $sql_game = $pdo->prepare("SELECT game_id AS id, game_name AS name, price FROM gg_game WHERE game_id IN ($placeholders)");
    $sql_game->execute($game_id);
    foreach ($sql_game->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $products['game-' . $row['id']] = $row;
    }
}

// ガジェット情報を取得
if (!empty($gadget_id)) {
    $placeholders = implode(',', array_fill(0, count($gadget_id), '?'));
    $sql_gadget = $pdo->prepare("SELECT gadget_id AS id, gadget_name AS name, price FROM gg_gadget WHERE gadget_id IN ($placeholders)");
    $sql_gadget->execute($gadget_id);
    foreach ($sql_gadget->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $products['gadget-' . $row['id']] = $row;
    }
}

// --------------------------------------------------
// (B) ここからが質問のループ部分（計算だけ行う）
// --------------------------------------------------

$total_price = 0; // 合計金額の初期化

foreach ($cart_item as $item) {
    $item_key = '';
    
    // キーを作成
    if (!empty($item['game_id'])) {
        $item_key = 'game-' . $item['game_id'];
    } else if (!empty($item['gadget_id'])) {
        $item_key = 'gadget-' . $item['gadget_id'];
    }

    // 商品情報があれば、計算する
    if (isset($products[$item_key])) {
        $product_info = $products[$item_key];
        
        // 小計を計算して合計に足す
        $subtotal = $product_info['price'] * $item['quantity'];
        $total_price += $subtotal;
    }
    // ★重要：ここでは echo (HTML表示) は一切しません！
}

// --------------------------------------------------
// (C) 計算が終わったらセッションに保存
// --------------------------------------------------

$_SESSION['order'] = [
    'cart' => $cart_item,  // カートの中身（IDと個数）
    'total' => $total_price // 今計算した合計金額
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
        <div class="checkout-container">
            <h1>配送先情報の入力</h1>

            <form action="checkout-confirm.php" method="POST">

                <div class="form-group">
                    <label>お名前</label>
                    <input type="text" name="name" value="<?php echo h($user_info['user_name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>郵便番号</label>
                    <input type="text" name="postcode" value="<?php echo h($user_info['postcode'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>住所</label>
                    <input type="text" name="address" value="<?php echo h($user_info['address'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>電話番号</label>
                    <input type="text" name="tel" value="<?php echo h($user_info['tel'] ?? ''); ?>" required>
                </div>

                <button type="submit" class="checkout-btn">確認画面へ</button>
            </form>
        </div>
    </main>
</body>
</html>