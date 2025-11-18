<?php 
session_start();
require_once '../header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG STORE - Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./css/cart.css">
</head>

<body>
    <main class="main-content">
        <div class="cart-container">
            <h1>ショッピングカート</h1> <div class="cart-layout">
                <?php
                require '../functions.php';
                

                $pdo = getPDO();

                $sql = $pdo -> prepare('SELECT * from gg_users WHERE user_id = ? ');
                $sql -> execute([$_SESSION['customer']['user_id']]);
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['user_id'] ?? '';
                // echo "（デバッグ）このIDでカートを探しています: ";

                // var_dump($user_id);
                                // 1. カート情報を取得
                $sql2 = $pdo->prepare("SELECT * FROM gg_carts WHERE user_id = ?");
                $sql2->execute([$user_id]);
                $cart_item = $sql2->fetchAll();

                $game_id = [];    // ゲームIDだけ集める配列

                $gadget_id = []; // ガジェットIDだけ集める配列

                // 2. IDを振り分ける

                foreach ($cart_item as $item) {

                    if (!empty($item['game_id'])) {
                        $game_id[] = $item['game_id'];
                    } else if (!empty($item['gadget_id'])) {
                        $gadget_id[] = $item['gadget_id'];
                    }

                }



                // (... 2. IDを振り分ける foreach ループの「後」 ...)



// ---------------------------------

// 3. 商品詳細（名前・価格）を取得

// ---------------------------------



$products = []; // ここに全商品情報をまとめる

// (A) ゲームIDがあれば、gamesテーブルからまとめて取得
if (!empty($game_id)) {
    // $game_id は [5, 8] のような配列
    // "IN (?,?)" のように ID の数だけ ? を自動で作成

    $placeholders = implode(',', array_fill(0, count($game_id), '?')); // 結果: "?,?"

    $sql_game = $pdo->prepare("SELECT game_id AS id, game_name AS name, price FROM gg_game WHERE game_id IN ($placeholders)");

    $sql_game->execute($game_id);

    // 取得した情報を $products 配列に ID をキーとして格納

    foreach ($sql_game->fetchAll() as $row) {
        $products['game-' . $row['id']] = $row; // 'game-5' => [...]
    }

}



// (B) ガジェットIDがあれば、gadgetsテーブルからまとめて取得

if (!empty($gadget_id)) {
    // $gadget_id は [3] のような配列
    $placeholders = implode(',', array_fill(0, count($gadget_id), '?')); // 結果: "?"

    $sql_gadget = $pdo->prepare("SELECT gadget_id AS id, gadget_name AS name, price FROM gg_gadget WHERE gadget_id IN ($placeholders)");

    $sql_gadget->execute($gadget_id);

    // 取得した情報を $products 配列に ID をキーとして格納

    foreach ($sql_gadget->fetchAll() as $row) {
        $products['gadget-' . $row['id']] = $row; // 'gadget-3' => [...]
    }

}



// (C) これで $products には、カート内の全商品の詳細情報が入った

// var_dump($products); // デバッグ用




// 合計金額を計算するための変数を0で用意

$total_price = 0;

// ★★★ ここからが cart-items の中身 ★★★
echo '<div class="cart-items">';

// 最初に取得した「カート情報（$cart_item）」をループ

// (こちらには「数量(quantity)」が入っているため)

foreach ($cart_item as $item) {
    $product_info = null; // 商品情報（名前、価格）
    $item_key = '';       // $products から探すためのキー

    // (D) $item がゲームかガジェットか判断

    if (!empty($item['game_id'])) {
        $item_key = 'game-' . $item['game_id'];
    } else if (!empty($item['gadget_id'])) {
        $item_key = 'gadget-' . $item['gadget_id'];
    }



    // (E) $products に該当キーの商品情報があるかチェック

    if (isset($products[$item_key])) {
        $product_info = $products[$item_key];
        // (F) 小計（単価 × 数量）を計算

        $subtotal = $product_info['price'] * $item['quantity'];
        // (G) 合計金額に加算
        $total_price += $subtotal;


        $raw_price = $product_info['price'];

        // gg_cartsテーブルの主キー(cart_id)を $item から取得
        // （あなたの var_dump (image_ca5a83.png) から 'cart_id' と特定）
        $cart_row_id = h($item['cart_id']);

        // (H) ★★★ ヒアドキュメント修正 ★★★

        // 関数呼び出しの結果を、先に変数に格納する

        $display_name = h($product_info['name']);
        $display_price = number_format($product_info['price']);
        $display_quantity = h($item['quantity']);

        // TODO: $image_path もここで設定する
        $image_path = "https://via.placeholder.com/100x100/333/808080?text=Item"; // TODO: 画像パス



        

        // 関数呼び出しの結果を、先に変数に格納する

        $display_name = h($product_info['name']);

        // ★重要: JSで使う「数値としての価格」も用意

        $raw_price = $product_info['price']; // JS計算用の数値
        $display_price = number_format($raw_price); // 表示用のフォーマット済み価格

       

        $display_quantity = h($item['quantity']);
        // TODO: $image_path もここで設定する
        $image_path = "https://via.placeholder.com/100x100/333/808080?text=Item"; // TODO: 画像パス



        // ヒアドキュメントでHTMLを出力

        // {$変数名} の形で変数を埋め込む

        echo <<< HTML

       

        <div class="cart-item" data-price="{$raw_price}">
            <div class="item-image">
                <img src="{$image_path}" alt="Product Image">
            </div>
            <div class="item-details">
                <div>
                    <div class="item-name">{$display_name}</div>
                    <div class="item-price">¥{$display_price}</div>
                </div>

                <div class="item-actions">

                    <input type="number" class="item-quantity" value="{$display_quantity}" min="1">

                    <a href="delete-cart.php" class="remove-link" data-cart-id="{$cart_row_id}">削除</a>

                </div>

            </div>

        </div>

HTML;

    }

}
echo '</div>'; // .cart-items 閉じ
// ---------------------------------
// 5. ご注文内容（サマリー）の表示
// ---------------------------------

$shipping_fee = 500; // 送料（固定）
// (PHPでの初回計算はそのまま残す)
$grand_total = $total_price + $shipping_fee;

// 関数呼び出しの結果を、先に変数に格納する
$display_total = number_format($total_price);
$display_shipping = number_format($shipping_fee);
$display_grand_total = number_format($grand_total);

// ヒアドキュメントでHTMLを出力

echo <<< HTML
<aside class="cart-summary">
    <h2>ご注文内容</h2>
    <div class="summary-line">
        <span>小計:</span>
        <span id="subtotal-display">¥{$display_total}</span>
    </div>

    <div class="summary-line">
        <span>送料:</span>
        <span id="shipping-display">¥{$display_shipping}</span>
    </div>

    <div class="summary-total">
        <span>合計:</span>
        <span id="grandtotal-display">¥{$display_grand_total}</span>
    </div>

    <button class="checkout-btn">レジに進む</button>
</aside>

HTML;

?>
    <script src="./js/cart.js"></script>

</body>

</html>