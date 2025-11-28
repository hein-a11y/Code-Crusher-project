<?php 
session_start();
require_once '../functions.php';

// ---
// 1. セッションチェック
// ---
if (empty($_SESSION['order']) || empty($_SESSION['order']['customer']) || empty($_SESSION['customer']['user_id'])) {
    header('Location: cart-input.php');
    exit();
}

$order = $_SESSION['order'];
$user_id = $_SESSION['customer']['user_id'];
$customer = $order['customer'];

// DBに入れる前に、正しい金額を計算する
$shipping_fee = 500;
$final_total_price = $_SESSION['order']['total'] + $shipping_fee;

$pdo = getPDO();

// =================================================================
// ★追加: 画面表示用に「商品名」などの詳細情報を取得する
// （cart-input.php と同じロジックです）
// =================================================================
$game_id = [];
$gadget_id = [];

foreach ($order['cart'] as $item) {
    if (!empty($item['game_id'])) {
        $game_id[] = $item['game_id'];
    } else if (!empty($item['gadget_id'])) {
        $gadget_id[] = $item['gadget_id'];
    }
}

$products = []; // ここに表示用の商品データが入ります

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
// =================================================================


// ---
// 2. データベース（DB）への登録処理
// ---
try {
    $pdo->beginTransaction();

    // (A) 注文テーブルへの保存
    $sql_order = $pdo->prepare(
        "INSERT INTO gg_orders (
            user_id, total, shipping_postal_code, shipping_address, shipping_phone_number, creation_date
        ) VALUES (?, ?, ?, ?, ?, NOW())"
    );
    
    $sql_order->execute([
        $user_id,
        $final_total_price,
        $customer['postcode'], 
        $customer['address'],   
        $customer['tel']       
    ]);

    $order_id = $pdo->lastInsertId();

    // (B) 注文詳細テーブルへの保存
    $sql_detail = $pdo->prepare(
        "INSERT INTO gg_detail_orders (order_id, game_id, gadget_id, quantity) 
         VALUES (?, ?, ?, ?)"
    );
    
    foreach ($order['cart'] as $item) {
        $sql_detail->execute([
            $order_id,
            $item['game_id'], 
            $item['gadget_id'], 
            $item['quantity']
        ]);
    }

    // 在庫を減らすためのSQL準備

        // ※ カラム名が 'stock' でない場合は、正しい名前に書き換えてください！
    $sql_update_game = $pdo->prepare("UPDATE gg_game SET stock = stock - ? WHERE game_id = ?");
    $sql_update_gadget = $pdo->prepare("UPDATE gg_gadget SET stock = stock - ? WHERE gadget_id = ?");
    // ループ処理
    foreach ($order['cart'] as $item) {
        // 1. 詳細テーブルに保存
        $sql_detail->execute([
            $order_id,
            $item['game_id'], 
            $item['gadget_id'], 
            $item['quantity']
        ]);

        // ★★★ 2. 在庫を減らす処理 ★★★
        if (!empty($item['game_id'])) {
            // ゲームの場合
            $sql_update_game->execute([
                $item['quantity'], // 減らす数
                $item['game_id']   // どのゲームか
            ]);
        } else if (!empty($item['gadget_id'])) {
            // ガジェットの場合
            $sql_update_gadget->execute([
                $item['quantity'], // 減らす数
                $item['gadget_id'] // どのガジェットか
            ]);
        }
    }

    //  カートを空にする
    $sql_delete_cart = $pdo->prepare("DELETE FROM gg_carts WHERE user_id = ?");
    $sql_delete_cart->execute([$user_id]);

    $pdo->commit();

    //  セッションを消す
    unset($_SESSION['order']);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<h3>エラーの正体:</h3>";
    debug($e->getMessage()); 
    exit();
}

        
require '../header.php';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ご注文完了</title>
    <link rel="stylesheet" href="./css/checkout.css">
    <style>
        .complete-container { max-width: 600px; margin: 40px auto; text-align: center; }
        .order-details-box { text-align: left; background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px; border: 1px solid #ddd; }
        .order-item { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 10px 0; }
        .order-total-area { margin-top: 20px; font-weight: bold; text-align: right; font-size: 1.2em; }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="complete-container">
            <h1>ご注文ありがとうございました！</h1>
            <p>以下の内容で注文を承りました。</p>

            <div class="order-details-box">
                <h3>注文内容 (注文ID: #<?php echo h($order_id); ?>)</h3>
                
                <?php foreach ($order['cart'] as $item): ?>
                    <?php 
                        // IDから商品情報を探す
                        $item_key = '';
                        if (!empty($item['game_id'])) $item_key = 'game-' . $item['game_id'];
                        elseif (!empty($item['gadget_id'])) $item_key = 'gadget-' . $item['gadget_id'];

                        // 商品情報が見つかれば表示
                        if (isset($products[$item_key])):
                            $p = $products[$item_key];
                            $subtotal = $p['price'] * $item['quantity'];
                    ?>
                        <div class="order-item">
                            <div>
                                <?php echo h($p['name']); ?> 
                                <span style="font-size:0.8em; color:#666;">
                                    (¥<?php echo number_format($p['price']); ?>)
                                </span>
                            </div>
                            <div>
                                x <?php echo h($item['quantity']); ?> 
                                = ¥<?php echo number_format($subtotal); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="order-total-area">
                    <p>送料: ¥<?php echo number_format($shipping_fee); ?></p>
                    <p>支払い総額: ¥<?php echo number_format($final_total_price); ?></p>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <a href="index.php" class="checkout-btn">トップページへ戻る</a>
            </div>
        </div>
    </main>
</body>
</html>