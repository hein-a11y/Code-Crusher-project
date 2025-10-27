<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="pageTitle">GG Store - Elden Ring</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    
    <link rel="stylesheet" href="./css/style.css">
    <script type="text/javascript" src="./js/script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>

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
</body>
</html>