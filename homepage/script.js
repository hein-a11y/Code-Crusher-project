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