<?php
require '../header.php';
require '../functions.php';
?>
<?php

if (empty($_SESSION['order'])) {
    header('Location: cart-input.php');
    exit();
}

$order = $_SESSION['order'];

$errors = [];

$name = $_POST['name'] ?? '';
$postcode = $_POST['postcode'] ?? '';
$address = $_POST['address'] ?? '';
$tel = $_POST['tel'] ?? '';

// ----------------------------------------------------
// 入力チェック（ロジックはそのまま）
// ----------------------------------------------------
if (empty($name)) {
    $errors[] = 'お名前が入力されていません。';
}
if (mb_strlen($name) > 50) {
    $errors[] = 'お名前は50文字以内で入力してください。';
}

if (empty($postcode)) {
    $errors[] = '郵便番号が入力されていません。';
} else {
    if (!preg_match("/^\d{3}-?\d{4}$/", $postcode)) {
        $errors[] = '郵便番号は正しい形式（例: 123-4567）で入力してください。';
    }
}

if (empty($address)) {
    $errors[] = '住所が入力されていません。';
}

if (empty($tel)) {
    $errors[] = '電話番号が入力されていません。';
} else {
    if (!preg_match("/^[0-9-]+$/", $tel)) {
        $errors[] = '電話番号は数字とハイフンで入力してください。';
    }
}

// ====================================================
// 判定結果による分岐
// ====================================================
if (count($errors) > 0) {
    // ※エラー表示のデザインは今回は割愛しますが、必要ならCSSに追加できます
    echo '<div style="background:#e74c3c; color:white; padding:20px; margin:20px;">';
    foreach ($errors as $error) {
        echo '<p>・' . h($error) . '</p>';
    }
    echo '<a href="checkout-input.php" style="color:white; text-decoration:underline;">入力画面に戻る</a>';
    echo '</div>';
    exit();
}

$_SESSION['order']['customer'] = [
    'name' => $name,
    'postcode' => $postcode,
    'address' => $address,
    'tel' => $tel
];

$shipping_fee = 500; // 送料
$grand_total = $order['total'] + $shipping_fee; // 合計金額

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご注文内容の確認</title>
    <link rel="stylesheet" href="./css/checkout.css">
</head>
<body>
<main class="main-content">
    <div class="checkout-wrapper">

        <div class="checkout-main">
            <h1>ご注文内容の確認</h1>
            <p>以下の内容で間違いがなければ、「注文を確定する」ボタンを押してください。</p>

            <h2>お届け先情報</h2>
            <div class="confirm-section">
                <p><strong>お名前:</strong> <?php echo h($name); ?></p>
                <p><strong>郵便番号:</strong> <?php echo h($postcode); ?></p>
                <p><strong>ご住所:</strong> <?php echo h($address); ?></p>
                <p><strong>電話番号:</strong> <?php echo h($tel); ?></p>
                <a href="checkout-input.php" class="back-link">内容を修正する</a>
            </div>

            <h2>ご請求金額</h2>
            <div class="confirm-section">
                <div class="price-breakdown">
                    <p>商品小計: ¥<?php echo number_format($order['total']); ?></p>
                    <p>送料: ¥<?php echo number_format($shipping_fee); ?></p>
                    <hr>
                    <p class="total-price">
                        合計（税込）: ¥<?php echo number_format($grand_total); ?>
                    </p>
                </div>
            </div>

            <form action="checkout-complete.php" method="POST">
                <button type="submit" class="checkout-btn">この内容で注文を確定する</button>
            </form>
        </div>
        <aside class="checkout-sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">ご利用ガイド</div>
                <ul class="sidebar-links">
                    <li><a href="#">お支払いについて</a></li>
                    <li><a href="#">配送について</a></li>
                    <li><a href="#">ご注文のキャンセルについて</a></li>
                </ul>
            </div>
        </aside>
        </div>
</main>
</body>
</html>