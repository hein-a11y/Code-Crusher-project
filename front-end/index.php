<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="pageTitle">GG Store - Elden Ring</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <style>
        /* Main Content Area */
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Slideshow Styles */
        .game-details-container { display: flex !important; gap: 2.5rem; background-color: var(--surface-color); padding: 2rem; border-radius: 8px; align-items: center; }
        .game-cover img { max-width: 350px; border-radius: 8px; }
        .game-info h1 { margin-top: 0; font-size: 2.5rem; color: #fff; }
        .game-info .developer { color: var(--text-secondary-color); font-size: 1.1rem; margin-bottom: 1rem; }
        .game-info .rating { color: #fdd835; margin-bottom: 1.5rem; }
        .game-info .price { font-size: 2.2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 1.5rem; }
        .buy-button { background-color: var(--primary-color); color: var(--background-color); border: none; padding: 1rem 2.5rem; font-size: 1.2rem; font-weight: bold; cursor: pointer; border-radius: 5px; transition: transform 0.2s, box-shadow 0.2s; }
        .buy-button:hover { transform: scale(1.05); box-shadow: 0 0 15px var(--primary-color); }
        .wishlist-link { color: var(--primary-color); text-decoration: none; margin-left: 1rem; font-weight: 500; }
        .card-slider-section { margin-top: 4rem; padding: 0 60px; }
        .card-slider-section h2 { font-size: 2rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 0.5rem; margin-bottom: 2rem; display: inline-block; }
        .card-slider .slick-slide { padding: 0 20px; }
        .gadget-card, .game-card {margin: 0 0.5em; background-color: var(--surface-color); border-radius: 8px; text-align: center; padding: 2rem; border: 1px solid #333; transition: transform 0.3s, box-shadow 0.3s; height: 95%; display: flex; flex-direction: column; justify-content: space-between; }
        .gadget-card:hover, .game-card:hover { transform: translateY(-5px); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); }
        .gadget-card img, .game-card img { max-width: 100%; height: 150px; object-fit: cover; margin-bottom: 1rem; border-radius: 4px; }
        .gadget-card h3, .game-card h3 { margin: 0.5rem 0; color: #fff; flex-grow: 1; }
        .gadget-card p, .game-card p { color: var(--text-secondary-color); font-size: 0.9rem; margin-bottom: 1.5rem; flex-grow: 1; }
        .learn-more-button { background-color: transparent; color: var(--primary-color); border: 1px solid var(--primary-color); padding: 0.6rem 1.5rem; border-radius: 5px; cursor: pointer; transition: background-color 0.3s, color 0.3s; margin: 1rem 0; }
        .learn-more-button:hover { background-color: var(--primary-color); color: var(--background-color); }
        .slick-prev, .slick-next { width: 45px; height: 70px; background: rgba(30, 30, 30, 0.5); z-index: 10; transition: background 0.3s ease; border-radius: 4px; }
        .slick-prev:hover, .slick-next:hover { background: rgba(0, 191, 255, 0.7); }
        .slick-prev { left: -55px; } .slick-next { right: -55px; }
        .slick-prev:before, .slick-next:before { content: ''; display: block; width: 12px; height: 12px; border-style: solid; border-color: white; border-width: 0 3px 3px 0; position: absolute; top: 50%; left: 50%; }
        .slick-next:before { transform: translate(-75%, -50%) rotate(-45deg); }
        .slick-prev:before { transform: translate(-25%, -50%) rotate(135deg); }
        .slick-dots li button:before { color: var(--text-secondary-color); }
        .slick-dots li.slick-active button:before { color: var(--primary-color); }

        @media (max-width: 992px) {
            .hamburger-menu { display: flex; }
            .nav-container { display: none; position: absolute; top: 100%; left: 0; width: 100%; background-color: var(--surface-color); flex-direction: column; align-items: flex-start; padding: 1rem 2rem; box-sizing: border-box; gap: 0; }
            .nav-container.active { display: flex; }
            nav ul { flex-direction: column; width: 100%; gap: 0; }
            nav ul li { width: 100%; padding: 1rem 0; border-bottom: 1px solid #333; }
            .header-actions { flex-direction: column; align-items: flex-start; width: 100%; padding-top: 1rem; gap: 1rem; }
            .search-bar { width: 95%; }
        }

        @media (max-width: 768px) {
            .game-details-container { flex-direction: column; text-align: center; }
            .game-cover img { max-width: 100%; margin-bottom: 1.5rem; }
            .game-info h1 { font-size: 2rem; }
            .game-info .price { font-size: 1.8rem; }
            .buy-button { display: block; margin: 0 auto 1rem auto; }
            .wishlist-link { margin: 0; }
            .slick-prev, .slick-next { display: none !important; }
            .card-slider-section { padding: 0; }
        }

        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            background-color: var(--surface-color);
            color: var(--text-secondary-color);
        }
    </style>
</head>
<body>
    <main>
        <div class="main-game-slider">
            <div class="game-details-container">
                <div class="game-cover"><img src="https://placehold.co/700x900/121212/e0e0e0?text=Elden+Ring" alt="Elden Ring Game Cover"></div>
                <div class="game-info">
                    <h1 data-i18n="mainSlider.game1.title"></h1>
                    <p class="developer" data-i18n="mainSlider.game1.dev"></p>
                    <div class="rating" data-i18n="mainSlider.game1.reviews"></div>
                    <div class="price">¥8,980</div>
                    <button class="buy-button" data-i18n="buyButton"></button>
                    <a href="#" class="wishlist-link" data-i18n="wishlistLink"></a>
                </div>
            </div>
            <div class="game-details-container">
                <div class="game-cover"><img src="https://placehold.co/700x900/ff003c/e0e0e0?text=Cyberpunk+2077" alt="Cyberpunk 2077 Game Cover"></div>
                <div class="game-info">
                    <h1 data-i18n="mainSlider.game2.title"></h1>
                    <p class="developer" data-i18n="mainSlider.game2.dev"></p>
                    <div class="rating" data-i18n="mainSlider.game2.reviews"></div>
                    <div class="price">¥7,980</div>
                    <button class="buy-button" data-i18n="buyButton"></button>
                    <a href="#" class="wishlist-link" data-i18n="wishlistLink"></a>
                </div>
            </div>
            <div class="game-details-container">
                <div class="game-cover"><img src="https://placehold.co/700x900/1e2954/e0e0e0?text=Starfield" alt="Starfield Game Cover"></div>
                <div class="game-info">
                    <h1 data-i18n="mainSlider.game3.title"></h1>
                    <p class="developer" data-i18n="mainSlider.game3.dev"></p>
                    <div class="rating" data-i18n="mainSlider.game3.reviews"></div>
                    <div class="price">¥9,700</div>
                    <button class="buy-button" data-i18n="buyButton"></button>
                    <a href="#" class="wishlist-link" data-i18n="wishlistLink"></a>
                </div>
            </div>
        </div>

        <section class="card-slider-section">
            <h2 data-i18n="recommendedGadgets.title"></h2>
            <div class="card-slider">
                <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/00bfff?text=Headset" alt="Gaming Headset">
                    <h3 data-i18n="recommendedGadgets.item1.title"></h3>
                    <p data-i18n="recommendedGadgets.item1.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
                <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/00bfff?text=Controller" alt="Gaming Controller">
                    <h3 data-i18n="recommendedGadgets.item2.title"></h3>
                    <p data-i18n="recommendedGadgets.item2.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
                <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/00bfff?text=Keyboard" alt="Gaming Keyboard">
                    <h3 data-i18n="recommendedGadgets.item3.title"></h3>
                    <p data-i18n="recommendedGadgets.item3.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
                 <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/00bfff?text=Mouse" alt="Gaming Mouse">
                    <h3 data-i18n="recommendedGadgets.item4.title"></h3>
                    <p data-i18n="recommendedGadgets.item4.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
            </div>
        </section>

        <section class="card-slider-section">
            <h2 data-i18n="popularGames.title"></h2>
            <div class="card-slider">
                <div class="game-card">
                    <img src="https://placehold.co/400x300/e60012/ffffff?text=Zelda" alt="Zelda">
                    <h3 data-i18n="popularGames.item1.title"></h3>
                    <p data-i18n="popularGames.item1.price"></p>
                    <button class="learn-more-button" data-i18n="viewGameButton"></button>
                </div>
                <div class="game-card">
                    <img src="https://placehold.co/400x300/2d2d2d/ffffff?text=Hollow+Knight" alt="Hollow Knight">
                    <h3 data-i18n="popularGames.item2.title"></h3>
                    <p data-i18n="popularGames.item2.price"></p>
                    <button class="learn-more-button" data-i18n="viewGameButton"></button>
                </div>
                <div class="game-card">
                    <img src="https://placehold.co/400x300/ffed00/000000?text=FFVII" alt="Final Fantasy VII">
                    <h3 data-i18n="popularGames.item3.title"></h3>
                    <p data-i18n="popularGames.item3.price"></p>
                    <button class="learn-more-button" data-i18n="viewGameButton"></button>
                </div>
                <div class="game-card">
                    <img src="https://placehold.co/400x300/003a6c/ffffff?text=Resident+Evil" alt="Resident Evil">
                    <h3 data-i18n="popularGames.item4.title"></h3>
                    <p data-i18n="popularGames.item4.price"></p>
                    <button class="learn-more-button" data-i18n="viewGameButton"></button>
                </div>
            </div>
        </section>

        <section class="card-slider-section">
            <h2 data-i18n="popularGadgets.title"></h2>
            <div class="card-slider">
                 <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/ffffff?text=VR+Headset" alt="VR Headset">
                    <h3 data-i18n="popularGadgets.item1.title"></h3>
                    <p data-i18n="popularGadgets.item1.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
                <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/ffffff?text=Gaming+Chair" alt="Gaming Chair">
                    <h3 data-i18n="popularGadgets.item2.title"></h3>
                    <p data-i18n="popularGadgets.item2.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
                <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/ffffff?text=4K+Monitor" alt="4K Monitor">
                    <h3 data-i18n="popularGadgets.item3.title"></h3>
                    <p data-i18n="popularGadgets.item3.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
                <div class="gadget-card">
                    <img src="https://placehold.co/400x300/1e1e1e/ffffff?text=Stream+Deck" alt="Stream Deck">
                    <h3 data-i18n="popularGadgets.item4.title"></h3>
                    <p data-i18n="popularGadgets.item4.desc"></p>
                    <button class="learn-more-button" data-i18n="learnMoreButton"></button>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p data-i18n="footer.copyright"></p>
    </footer>

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>

    <script type="text/javascript">
        // --- i18next Translation Script ---
        const resources = {
            en: {
                translation: {
                    "pageTitle": "GG Store - Elden Ring",
                    "nav": { "home": "HOME", "games": "GAMES", "gadgets": "GADGETS", "reviews": "REVIEWS", "contact": "CONTACT" },
                    "searchPlaceholder": "Search...",
                    "mainSlider": {
                        "game1": { "title": "Elden Ring (Digital Download)", "dev": "FromSoftware / Bandai Namco", "reviews": "★★★★☆ (2,530 reviews)" },
                        "game2": { "title": "Cyberpunk 2077 (Digital Download)", "dev": "CD PROJEKT RED", "reviews": "★★★★★ (10,480 reviews)" },
                        "game3": { "title": "Starfield (Digital Download)", "dev": "Bethesda Game Studios", "reviews": "★★★★☆ (5,120 reviews)" }
                    },
                    "buyButton": "BUY DIGITAL",
                    "wishlistLink": "ADD TO WISHLIST",
                    "recommendedGadgets": {
                        "title": "Recommended Gadgets",
                        "item1": { "title": "VOID PRO RGB Headset", "desc": "High-fidelity audio for immersion" },
                        "item2": { "title": "STRY.NOVA Controller", "desc": "Ergonomic design for long sessions" },
                        "item3": { "title": "SENTINEL ELITE", "desc": "Mechanical keys for precision input" },
                        "item4": { "title": "RAZER VIPER 8K", "desc": "Ultra-responsive for competitive play" }
                    },
                    "popularGames": {
                        "title": "Popular Games",
                        "item1": { "title": "Breath of the Wild 2", "price": "¥7,980" },
                        "item2": { "title": "Hollow Knight: Silksong", "price": "¥3,500" },
                        "item3": { "title": "Final Fantasy VII Rebirth", "price": "¥9,800" },
                        "item4": { "title": "Resident Evil 9", "price": "¥8,980" }
                    },
                    "popularGadgets": {
                        "title": "Popular Gadgets",
                        "item1": { "title": "AURA VR PRO", "desc": "Next-generation virtual reality" },
                        "item2": { "title": "ERGO-COMMANDER", "desc": "Ultimate comfort for extended play" },
                        "item3": { "title": "CRYSTAL 4K 144Hz", "desc": "Stunning visuals and smooth gameplay" },
                        "item4": { "title": "StreamDeck XL", "desc": "Master your content creation" }
                    },
                    "learnMoreButton": "LEARN MORE",
                    "viewGameButton": "VIEW GAME",
                    "footer": { "copyright": "&copy; 2025 GG Store by CODE CRUSHER. All Rights Reserved." }
                }
            },
            ja: {
                translation: {
                    "pageTitle": "GG Store - エルデンリング",
                    "nav": { "home": "ホーム", "games": "ゲーム", "gadgets": "ガジェット", "reviews": "レビュー", "contact": "お問い合わせ" },
                    "searchPlaceholder": "検索...",
                    "mainSlider": {
                        "game1": { "title": "エルデンリング (デジタル)", "dev": "フロム・ソフトウェア / バンダイナムコ", "reviews": "★★★★☆ (2,530 レビュー)" },
                        "game2": { "title": "サイバーパンク2077 (デジタル)", "dev": "CD PROJEKT RED", "reviews": "★★★★★ (10,480 レビュー)" },
                        "game3": { "title": "スターフィールド (デジタル)", "dev": "ベセスダ・ゲーム・スタジオ", "reviews": "★★★★☆ (5,120 レビュー)" }
                    },
                    "buyButton": "デジタル版を購入",
                    "wishlistLink": "ウィッシュリストに追加",
                    "recommendedGadgets": {
                        "title": "おすすめジェット",
                        "item1": { "title": "VOID PRO RGBヘッドセット", "desc": "没入感のための高忠実度オーディオ" },
                        "item2": { "title": "STRY.NOVAコントローラー", "desc": "長時間のセッションのための人間工学的デザイン" },
                        "item3": { "title": "SENTINEL ELITE", "desc": "精密な入力のためのメカニカルキー" },
                        "item4": { "title": "RAZER VIPER 8K", "desc": "競技プレイのための超高感度" }
                    },
                    "popularGames": {
                        "title": "人気ゲーム",
                        "item1": { "title": "ブレス オブ ザ ワイルド 2", "price": "¥7,980" },
                        "item2": { "title": "ホロウナイト: シルクソング", "price": "¥3,500" },
                        "item3": { "title": "ファイナルファンタジーVII リバース", "price": "¥9,800" },
                        "item4": { "title": "バイオハザード9", "price": "¥8,980" }
                    },
                    "popularGadgets": {
                        "title": "人気のガジェット",
                        "item1": { "title": "AURA VR PRO", "desc": "次世代のバーチャルリアリティ" },
                        "item2": { "title": "ERGO-COMMANDER", "desc": "長時間のプレイでも最高の快適性" },
                        "item3": { "title": "CRYSTAL 4K 144Hz", "desc": "美しいビジュアルと滑らかなゲームプレイ" },
                        "item4": { "title": "StreamDeck XL", "desc": "コンテンツ制作をマスターしよう" }
                    },
                    "learnMoreButton": "詳細を見る",
                    "viewGameButton": "ゲームを見る",
                    "footer": { "copyright": "&copy; 2025 GG Store by CODE CRUSHER. 無断複写・転載を禁じます。" }
                }
            }
        };

        $(document).ready(function(){
            // --- i18next Initialization ---
            i18next
                .use(i18nextBrowserLanguageDetector)
                .init({
                    resources,
                    fallbackLng: 'ja',
                    debug: false,
                    interpolation: { escapeValue: false }
                }, function(err, t) {
                    // Init jquery-i18next
                    jqueryI18next.init(i18next, $, { useOptionsAttr: true });
                    
                    // Initial translation
                    updateLanguageUI(i18next.language);

                    // Initialize Slick Carousel AFTER content is translated
                    initializeSlick();
                });

            // --- Event Handlers ---
            $('#btn-en').on('click', () => changeLang('en'));
            $('#btn-ja').on('click', () => changeLang('ja'));
            $('.hamburger-menu').on('click', () => $('.nav-container').toggleClass('active'));
            
            // --- Functions ---
            function changeLang(lang) {
                i18next.changeLanguage(lang, () => updateLanguageUI(lang));
            }

            function updateLanguageUI(lang) {
                $('html').attr('lang', lang);
                $('.lang-switcher button').removeClass('active');
                $(`#btn-${lang}`).addClass('active');
                $('body').localize();
            }
            
            function initializeSlick() {
                $('.main-game-slider').slick({
                    dots: true, infinite: true, speed: 500, slidesToShow: 1, adaptiveHeight: true, autoplay: true, autoplaySpeed: 4000, arrows: true
                });

                $('.card-slider').slick({
                    dots: true, infinite: false, speed: 300, slidesToShow: 3, slidesToScroll: 1,
                    responsive: [
                        { breakpoint: 1024, settings: { slidesToShow: 2, slidesToScroll: 1, infinite: true, dots: true } },
                        { breakpoint: 768, settings: { slidesToShow: 1, slidesToScroll: 1 } }
                    ]
                });
            }
        });
    </script>

</body>
</html>