<?php
    require '../header.php';
    require '../functions.php';
?>
<?php


if (empty($_SESSION['order'])) {
    header('Location: cart-input.php'); // $_SESSION['order']に何も入ってなければカートに戻す
    exit();
}

$order = $_SESSION['order'];


// エラーを溜めておくための「箱（配列）」を用意する
$errors = [];

// 送られてきたデータを受け取る（まだセッションには入れない）
$name = $_POST['name'] ?? '';
$postcode = $_POST['postcode'] ?? '';
$address = $_POST['address'] ?? '';
$tel = $_POST['tel'] ?? '';


// 1. お名前のチェック

// 「もし、名前が空っぽだったら」
if (empty($name)) {
    $errors[] = 'お名前が入力されていません。';
}
// 「もし、名前が長すぎたら（例: 50文字以上）」
if (mb_strlen($name) > 50) {
    $errors[] = 'お名前は50文字以内で入力してください。';
}


// 2. 郵便番号のチェック

if (empty($postcode)) {
    $errors[] = '郵便番号が入力されていません。';
} else {
    // ★ポイント: 正規表現を使う
    // 「数字3桁 - 数字4桁」または「数字7桁」か？
    if (!preg_match("/^\d{3}-?\d{4}$/", $postcode)) {
        $errors[] = '郵便番号は正しい形式（例: 123-4567）で入力してください。';
    }
}


// 3. 住所のチェック

if (empty($address)) {
    $errors[] = '住所が入力されていません。';
}

// 4. 電話番号のチェック

if (empty($tel)) {
    $errors[] = '電話番号が入力されていません。';
} else {
    // 「数字とハイフンだけで構成されているか？」
    if (!preg_match("/^[0-9-]+$/", $tel)) {
        $errors[] = '電話番号は数字とハイフンで入力してください。';
    }
}


// 判定結果による分岐


// もし $errors に何か入っていたら（＝エラーがあったら）
if (count($errors) > 0) {
   
    // エラーを表示して、処理を止める（または入力画面に戻す）
    echo '<div class="error-container">';
    foreach ($errors as $error) {
        echo '<p class="error-msg">・' . h($error) . '</p>';
    }
    echo '<a href="checkout-input.php" class="back-btn">入力画面に戻る</a>';
    echo '</div>';
   
    // フッターなどを読み込んで終了
    exit(); // ここでストップ！下の確認画面は見せない

}

// エラーがなければ（count($errors) === 0）、
// ここで初めてセッションに保存して、確認画面のHTMLを表示する
$_SESSION['order']['customer'] = [
    'name' => $name,
    'postcode' => $postcode,
    'address' => $address,
    'tel' => $tel
];

$shipping_fee = 500; // 送料
$grand_total = $order['total'] + $shipping_fee; // 合計金額（商品代 + 送料）

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
        <div class="checkout-container">
            <h1>ご注文内容の確認</h1>

            <div class="confirm-section">
                <h2>お届け先情報</h2>
                <p><strong>お名前:</strong> <?php echo h($name); ?></p>
                <p><strong>郵便番号:</strong> <?php echo h($postcode); ?></p>
                <p><strong>ご住所:</strong> <?php echo h($address); ?></p>
                <p><strong>電話番号:</strong> <?php echo h($tel); ?></p>
               
                <a href="checkout-input.php" class="back-link">修正する</a>
            </div>

           <div class="confirm-section">
                <h2>ご請求金額</h2>
                <div class="price-breakdown">
                    <p>小計: ¥<?php echo number_format($order['total']); ?></p>
                   
                    <p>送料: ¥<?php echo number_format($shipping_fee); ?></p>
                   
                    <hr>
                   
                    <p class="total-price">
                        <strong>合計: ¥<?php echo number_format($grand_total); ?></strong>
                    </p>
                </div>
            </div>

            <form action="checkout-complete.php" method="POST">
                <button type="submit" class="checkout-btn">この内容で注文を確定する</button>
            </form>

        </div>
    </main>
</body>
</html>