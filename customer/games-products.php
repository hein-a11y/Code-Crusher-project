<?php require_once '../header.php'; ?>
<?php require '../functions.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME STORE - 商品一覧</title>
    
    <link rel="stylesheet" href="./css/game.css">

</head>
<body>
    <div class="container"> 
        <main>
            <div class="game-grid">
                <?php

                $pdo = getPDO();

                if (isset($_GET['keyword'])) {
                    $sql = $pdo->prepare('SELECT * FROM gg_game WHERE game_name LIKE ?');
                    $sql->execute(['%' . $_GET['keyword'] . '%']);
                } else {
                    $sql = $pdo->query('SELECT * FROM gg_game');
                }
                
                foreach ($sql as $row) {
                    $id = h($row['game_id']);
                    $name = h($row['game_name']);
                    $manufacturer = h($row['manufacturer']);
                    $connectivity = h($row['game_type']);
                    $price_number_format = number_format(h($row['price']));
                    $keyword = $_GET['keyword'] ?? '';
                    $img_src = "./game-images/game-$id" . "_1.jpg";
                    
                    echo <<< HTML
                    <div class="product-card">
                        <a href="./gadget-details.php?name={$name}&keyword={$keyword}&id={$id}">
                            <img src="$img_src" alt="商品画像">
                                <div class="product-info">
                                    <p class="product-brand">{$manufacturer}</p>
                                    <h2 class="product-title">{$name}</h2>
                                    <p class="product-price">¥{$price_number_format} <span class="price-tax">(税込)</span></p>
                                    <ul class="product-features"><li>{$connectivity}</li></ul>
                                </div>
                        </a>
                    </div>
                    
                    HTML;
                }
                ?>
            </div>
        </main>
    </div>