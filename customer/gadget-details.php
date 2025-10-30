<?php require_once '../header.php'; ?>
<?php require '../functions.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG STORE - 商品詳細</title>
    
    <!-- Google Fonts (Inter) を読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- 読み込むCSSファイルのパスを修正 -->
    <link rel="stylesheet" href="./css/gadget-details.css">
</head>
<body>
    <!-- メインコンテンツ -->
    <main class="main-content">
        <div class="container">
            
            <!-- パンくずリスト -->
            <div class="breadcrumb">
                <a href="./index.php">ホーム</a> &gt; <a href="#">ガジェット</a> &gt; <span>Logicool G PRO X 2 LIGHTSPEED</span>
            </div>

            <!-- 商品詳細（ギャラリー + 情報） -->
            <div class="product-detail-container">
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
                <!-- 商品ギャラリー -->
                <div class="product-gallery">
                    <div class="main-image-wrapper">
                        <!-- メイン画像 -->
                        <img id="main-image" src="$img_src" alt="$name メイン画像">
                    </div>
                    <!-- サムネイル -->
                    <div class="thumbnail-container">
            HTML;

            for ($i = 1; $i <= $images; $i++) {
                $img_src = "./gadget-images/gadgets-$id" . "_$i.jpg";
                echo <<< HTML
                    <img class="thumbnail" src="$img_src" alt="サムネイル $i" data-src="$img_src">
                HTML;
            }
            echo <<< HTML
                    </div>
                </div>
                <!-- 商品情報 & アクション -->
                <div class="product-info">
                    <div>
                        <span class="brand">$manufacturer</span>
                        <h1 class="product-name">$name</h1>
                        <div class="price">
                            ¥$price <span>(税込)</span>
                        </div>
                HTML;
            ?>

                        <div class="features">
                            <!-- 画像のタグを再現 -->
                            <span class="feature-tag">Bluetooth</span>
                            <span class="feature-tag">LIGHTSPEEDワイヤレス</span>
                            <span class="feature-tag">有線</span>
                        </div>
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="actions">
                        <button class="action-button add-to-cart">カートに入れる</button>
                        <button class="action-button buy-now">今すぐ購入</button>
                    </div>
                </div>
            </div>

            <!-- 商品説明 -->
            <section class="product-details-section product-description">
                <h2 class="section-title">商品説明</h2>
                <p>プロレベルのパフォーマンスと快適性を追求した、G PRO X 2 LIGHTSPEEDワイヤレスゲーミングヘッドセット。革新的なPRO-G 50mmドライバーが、かつてないクリアさと定位感を実現します。LIGHTSPEEDワイヤレス技術により、遅延のない安定した接続を提供し、長時間のバッテリー寿命で集中力を途切れさせません。</p>
                <p>Blue VO!CEマイクテクノロジー（ソフトウェア経由）を搭載し、スタジオ品質のクリアな音声チャットが可能。取り外し可能なマイクブームと、DTS Headphone:X 2.0 7.1chサラウンドサウンドが、没入感あふれるゲーミング体験を約束します。</p>
            </section>

            <!-- 製品仕様 -->
            <section class="product-details-section product-specs">
                <h2 class="section-title">製品仕様</h2>
                <table>
                    <tbody>
                        <tr>
                            <th>接続タイプ</th>
                            <td>LIGHTSPEEDワイヤレス, Bluetooth, 3.5mm有線</td>
                        </tr>
                        <tr>
                            <th>ドライバー</th>
                            <td>PRO-G 50mm グラフェン</td>
                        </tr>
                        <tr>
                            <th>マイク</th>
                            <td>着脱式6mm（Blue VO!CE対応）</td>
                        </tr>
                        <tr>
                            <th>バッテリー持続時間</th>
                            <td>最大50時間</td>
                        </tr>
                        <tr>
                            <th>重量</th>
                            <td>345g</td>
                        </tr>
                        <tr>
                            <th>サラウンドサウンド</th>
                            <td>DTS Headphone:X 2.0</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- 関連商品 -->
            <section class="product-details-section related-products">
                <h2 class="section-title">関連商品</h2>
                <!-- クラス名を "product-grid" から "product-scroll-container" に変更 -->
                <div class="product-scroll-container">
                    <!-- カード1 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Mouse" alt="Logicool G Pro X Superlight">
                        <span class="brand">Logicool</span>
                        <h3 class="name">Logicool G Pro X Superlight</h3>
                        <div class="price">¥17,800 <span>(税込)</span></div>
                    </a>
                    <!-- カード2 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Keyboard" alt="SteelSeries Apex Pro TKL">
                        <span class="brand">SteelSeries</span>
                        <h3 class="name">SteelSeries Apex Pro TKL</h3>
                        <div class="price">¥25,000 <span>(税込)</span></div>
                    </a>
                    <!-- カード3 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Controller" alt="Xbox Elite ワイヤレス コントローラー">
                        <span class="brand">Microsoft</span>
                        <h3 class="name">Xbox Elite ワイヤレス コントローラー シリーズ 2</h3>
                        <div class="price">¥19,980 <span>(税込)</span></div>
                    </a>
                    <!-- カード4 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Mic" alt="Shure MV7">
                        <span class="brand">Shure</span>
                        <h3 class="name">Shure MV7</h3>
                        <div class="price">¥31,000 <span>(税込)</span></div>
                    </a>
                    <!-- カード5 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Mouse+2" alt="ZOWIE EC2-C">
                        <span class="brand">BenQ ZOWIE</span>
                        <h3 class="name">ZOWIE EC2-C</h3>
                        <div class="price">¥8,800 <span>(税込)</span></div>
                    </a>
                    <!-- スクロール確認用に追加 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Mouse+3" alt="追加商品 1">
                        <span class="brand">Logicool</span>
                        <h3 class="name">Logicool G502 X</h3>
                        <div class="price">¥9,800 <span>(税込)</span></div>
                    </a>
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Keyboard+2" alt="追加商品 2">
                        <span class="brand">Razer</span>
                        <h3 class="name">Razer Huntsman V3 Pro</h3>
                        <div class="price">¥36,800 <span>(税込)</span></div>
                    </a>
                </div>
            </section>

            <!-- ▼▼▼ 新しく追加するセクション ▼▼▼ -->
            <!-- おすすめ商品 -->
            <section class="product-details-section recommended-products">
                <h2 class="section-title">おすすめ商品</h2>
                <!-- こちらも "product-scroll-container" クラスを使用 -->
                <div class="product-scroll-container">
                    <!-- カード1 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Rec+1" alt="おすすめ 1">
                        <span class="brand">SteelSeries</span>
                        <h3 class="name">Arctis Nova Pro Wireless</h3>
                        <div class="price">¥52,000 <span>(税込)</span></div>
                    </a>
                    <!-- カード2 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Rec+2" alt="おすすめ 2">
                        <span class="brand">Razer</span>
                        <h3 class="name">Razer Viper V3 Pro</h3>
                        <div class="price">¥26,480 <span>(税込)</span></div>
                    </a>
                    <!-- カード3 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Rec+3" alt="おすすめ 3">
                        <span class="brand">Finalmouse</span>
                        <h3 class="name">UltralightX (Lion)</h3>
                        <div class="price">¥31,800 <span>(税込)</span></div>
                    </a>
                    <!-- カード4 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Rec+4" alt="おすすめ 4">
                        <span class="brand">Shure</span>
                        <h3 class="name">SM7B</h3>
                        <div class="price">¥49,800 <span>(税込)</span></div>
                    </a>
                    <!-- カード5 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Rec+5" alt="おすすめ 5">
                        <span class="brand">Elgato</span>
                        <h3 class="name">Stream Deck +</h3>
                        <div class="price">¥29,800 <span>(税込)</span></div>
                    </a>
                    <!-- カード6 -->
                    <a href="#" class="product-card">
                        <img src="https://placehold.co/220x220/333/f0f0f0?text=Rec+6" alt="おすすめ 6">
                        <span class="brand">BenQ ZOWIE</span>
                        <h3 class="name">XL2566K 360Hz</h3>
                        <div class="price">¥98,000 <span>(税込)</span></div>
                    </a>
                </div>
            </section>
            <!-- ▲▲▲ 追加セクションここまで ▲▲▲ -->
            
        </div>
    </main>

    <!-- フッター -->
    <footer class="site-footer">
        <div class="container">
            <p>© 2025 GG STORE. All rights reserved.</p>
        </div>
    </footer>

    <!-- 読み込むJavaScriptファイルのパスを修正 -->
    <script src="./js/gadget-details.js" defer></script>

</body>
</html>



