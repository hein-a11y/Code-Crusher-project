<?php require '../header-input.php'; ?>
<?php require '../functions.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADGET STORE - 商品一覧</title>
    
    <link rel="stylesheet" href="./css/gadgets.css">

</head>
<body>
    <div class="container"> <main>
            <div class="product-grid">
                <div class="product-card">
                    <img src="https://via.placeholder.com/400x250/333333/FFFFFF?text=Keyboard+1" alt="商品画像">
                    <div class="product-info">
                        <?php

                        $pdo = getPDO();
                        
                        foreach ($pdo as $row) {
                            echo <<< HTML
                            <p class="product-brand">{$row['manufacturer']}</p>
                            <h2 class="product-title">{$row['gadget_name']}</h2>
                            <p class="product-price">¥{$row['price']} <span class="price-tax">(税込)</span></p>
                            <ul class="product-features"><li>{$row['connectivity_type']}</li></ul>
                            HTML;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>