<?php require_once '../header.php'; ?>
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
                // $game_ids は [5, 8] など
                // $gadget_ids は [3] など

                ?>
                <div class="cart-items">
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/100x100/333/808080?text=Item+1" alt="Product Image">
                        </div>
                        <div class="item-details">
                            <div>
                                <div class="item-name">ゲーミングマウス</div> <div class="item-price">¥8,000</div>
                        </div>
                            <div class="item-actions">
                                <input type="number" class="item-quantity" value="1" min="1">
                                <a href="#" class="remove-link">削除</a> </div>
                        </div>
                    </div>

                    <div class="cart-item">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/100x100/333/808080?text=Item+2" alt="Product Image">
                        </div>
                        <div class="item-details">
                            <div>
                                <div class="item-name">メカニカルキーボード</div> <div class="item-price">¥15,000</div>
                            </div>
                            <div class="item-actions">
                                <input type="number" class="item-quantity" value="1" min="1">
                                <a href="#" class="remove-link">削除</a> </div>
                        </div>
                    </div>
                </div>

                <aside class="cart-summary">
                    <h2>ご注文内容</h2> <div class="summary-line">
                        <span>小計:</span> <span>¥23,000</span>
                    </div>
                    <div class="summary-line">
                        <span>送料:</span> <span>¥500</span>
                    </div>
                    <div class="summary-total">
                        <span>合計:</span> <span>¥23,500</span>
                    </div>
                    <button class="checkout-btn">
                        レジに進む </button>
                </aside>

            </div>
        </div>
    </main>

    <script src="script.js"></script>
    
</body>
</html>