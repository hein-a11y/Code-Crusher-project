<?php require_once '../header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADGET STORE - 商品一覧</title>
    
    <link rel="stylesheet" href="./css/gadgets.css">

</head>
<body>
    <div class="container"> 
        <main>
            <div class="product-grid">
                <?php

                $pdo = getPDO();

                if (isset($_GET['keyword'])) {
                    $sql = $pdo->prepare('SELECT * FROM gg_gadget WHERE gadget_name LIKE ?');
                    $sql->execute(['%' . $_GET['keyword'] . '%']);
                } else {
                    $sql = $pdo->query('SELECT * FROM gg_gadget');
                }

                
                foreach ($sql as $row) {
                    $id = h($row['gadget_id']);
                    $name = h($row['gadget_name']);
                    $manufacturer = h($row['manufacturer']);
                    $connectivity = h($row['connectivity_type']);
                    $price_number_format = number_format(h($row['price']));
                    $keyword = $_GET['keyword'] ?? '';

                    $sql = $pdo->prepare('SELECT gg_media.url,gg_media.is_primary FROM gg_media WHERE gg_media.gadget_id = ?');
                    $sql->execute([$id]);
                    $medias = $sql->fetchAll(PDO::FETCH_ASSOC);

                    // $sql = $pdo->prepare('SELECT gg_media.is_primary FROM gg_media INNER JOIN gg_gadget ON gg_media.media_id = gg_gadget.gadget_id WHERE gg_gadget.gadget_id = ?');
                    // $sql->execute([$id]);
                    // $is_primary = $sql->fetchAll(PDO::FETCH_ASSOC);

                    // for ($i = 0; $i < count($url); $i++) {
                    //     if ($is_primary[$i]['is_primary'] == 1) {
                    //         $img_src = h($url[$i]['url']);
                    //         break;
                    //     } else {
                    //         $img_src = h($url[0]['url']);
                    //     }
                    // }
                    $main_img = "";
                    foreach ($medias as $media) {
                        if ($media['is_primary'] == 1) {
                            $main_img = h($media['url']);
                            break;
                        } else {
                            $img_src = h($media['url']);
                        }
                    }
                    debug($main_img);
                    echo <<< HTML
                    <div class="product-card">
                        <a href="./gadget-details.php?name={$name}&keyword={$keyword}&id={$id}">
                            <img src="{$main_img}" alt="商品画像">
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

</body>
</html>

<a href="./gadget-images/gadgets-1_1.jpg"></a>