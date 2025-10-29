<?php require_once '../header.php'; ?>
<?php require '../functions.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logicool G Pro X Superlight 2 - GG STORE</title>
    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./css/gadget-details.css">
    <script src="./js/gadget-details.js" defer></script>
    
</head>
<body>
    <!-- メインコンテンツ: 商品詳細 -->
    <main class="container main-container">
        <div class="product-grid">
            <?php
            $pdo = getPDO();
            $sql = $pdo->prepare('SELECT * FROM gg_gadget WHERE gadget_id = ?');
            $sql->execute([$_GET['id']]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $id = h($row['gadget_id']);
            $name = h($row['gadget_name']);
            $explanation = h($row['gadget_explanation']);
            $manufacturer = h($row['manufacturer']);
            $connectivity_type = h($row['connectivity_type']);
            $price = number_format(h($row['price']));
            $images = h($row['images']);

            $img_src = "./gadget-images/gadgets-$id" . "_1.jpg";

            echo <<< HTML
            <!-- 商品画像ギャラリー -->
            <div class="gallery">
                <div class="gallery-main-img-wrap">
                    <!-- メイン画像 -->
                    <img id="mainImage" src="$img_src" 
                        alt="$name 画像" 
                        class="gallery-main-img">
                </div>
                <!-- サムネイル画像 -->
                <div class="gallery-thumbs" id="thumbnailContainer">
            HTML;

            for ($i = 1; $i <= $images; $i++) {
                $img_src = "./gadget-images/gadgets-$id" . "_$i.jpg";
                // 1番目のボタンかどうかを判定
                $active_class = ($i == 1) ? 'active' : '';
                echo <<< HTML
                    <button class="thumb-btn active" $active_class data-src="$img_src">
                        <img src="$img_src" alt="サムネイル $i" class="thumb-img">
                    </button>
                HTML;
            }
            echo <<< HTML
                </div>
            </div>
            HTML;
            ?>
            <!-- 商品情報・購入 -->
            <div class="details">
                <div>
                    <span class="details-brand">Logicool</span>
                    <h1 class="details-title">Logicool G Pro X Superlight 2 ワイヤレス ゲーミングマウス</h1>
                </div>

                <div class="details-price">
                    ¥24,800 <span class="price-suffix">(税込)</span>
                </div>

                <!-- カラー選択 -->
                <div class="color-select">
                    <h3 class="color-label">カラー: <span id="selectedColor" class="color-name">ブラック</span></h3>
                    <div class="color-swatches" id="colorSwatchContainer">
                        <button class="color-swatch sw-black active" data-color="ブラック" aria-label="ブラック"></button>
                        <button class="color-swatch sw-white" data-color="ホワイト" aria-label="ホワイト"></button>
                        <button class="color-swatch sw-pink" data-color="マゼンタ" aria-label="マゼンタ"></button>
                    </div>
                </div>

                <!-- カートに入れるボタン -->
                <button class="btn-primary">
                    カートに入れる
                </button>

                <!-- 商品説明 -->
                <div class="desc">
                    <h2 class="section-title">商品説明</h2>
                    <p class="desc-text">
                        次世代のプログレードワイヤレスマウス、G Pro X Superlight 2が登場。
                        わずか60gの超軽量設計と、HERO 2センサーによる比類なき精度を実現しました。
                        LIGHTSPEEDワイヤレステクノロジーが、遅延のない安定した接続を提供し、LIGHTFORCEハイブリッドスイッチが高速かつ高耐久なクリックを可能にします。
                        プロのeスポーツ選手と共に設計されたこのマウスで、あなたのパフォーマンスを次のレベルへ。
                    </p>
                </div>

                <!-- 製品仕様 -->
                <div class="specs">
                    <h2 class="section-title">製品仕様</h2>
                    <ul class="specs-list">
                        <li class="spec-item"><span class="spec-label">重量:</span> 60g</li>
                        <li class="spec-item"><span class="spec-label">センサー:</span> HERO 2</li>
                        <li class="spec-item"><span class="spec-label">最大解像度:</span> 32,000 DPI</li>
                        <li class="spec-item"><span class="spec-label">接続:</span> LIGHTSPEED ワイヤレス</li>
                        <li class="spec-item"><span class="spec-label">スイッチ:</span> LIGHTFORCE (ハイブリッド)</li>
                        <li class="spec-item"><span class="spec-label">バッテリー:</span> 最大95時間</li>
                        <li class="spec-item"><span class="spec-label">互換性:</span> PC / Mac (USBポート)</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- フッター -->
    <footer class="footer">
        <div class="container footer-container">
            <div class="footer-links">
                <a href="#" class="footer-link">会社概要</a>
                <a href="#" class="footer-link">利用規約</a>
                <a href="#" classs="footer-link">プライバシーポリシー</a>
                <a href="#" class="footer-link">お問い合わせ</a>
            </div>
            <p class="copyright">&copy; 2025 GG STORE. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

