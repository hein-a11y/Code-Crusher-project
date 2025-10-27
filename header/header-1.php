<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="pageTitle">GG Store - Elden Ring</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <style>
        /* General Theme & Body Styling */
        :root {
            --primary-color: #00bfff; /* Neon blue accent */
            --background-color: #121212; /* Deep black */
            --surface-color: #1e1e1e;   /* Slightly lighter for cards/headers */
            --text-color: #e0e0e0;      /* Light grey for readability */
            --text-secondary-color: #a0a0a0; /* Dimmer text */
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header & Navigation */
        header {
            background-color: var(--surface-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            position: relative;
            z-index: 100;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
        }

        .nav-container {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 1.5rem;
        }

        nav a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--primary-color);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        /* Language Switcher Styles */
        .lang-switcher button {
            background: none;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.3rem 0.6rem;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .lang-switcher button:hover, .lang-switcher button.active {
            background-color: var(--primary-color);
            color: var(--background-color);
        }


        .search-bar {
            background-color: #333;
            border: 1px solid #555;
            color: var(--text-color);
            padding: 0.5rem;
            border-radius: 5px;
        }
        
        .hamburger-menu {
            display: none;
            cursor: pointer;
            flex-direction: column;
            gap: 5px;
        }

        .hamburger-menu .bar {
            width: 25px;
            height: 3px;
            background-color: var(--text-color);
            border-radius: 3px;
            transition: all 0.3s ease-in-out;
        }

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

    <header>
        <div class="logo">GG STORE</div>
        
        <div class="hamburger-menu">
            <div class="bar"></div><div class="bar"></div><div class="bar"></div>
        </div>

        <div class="nav-container">
            <nav>
                <ul>
                    <li><a href="index.php" data-i18n="nav.home"></a></li>
                    <li><a href="games.php" data-i18n="nav.games"></a></li>
                    <li><a href="gadgets.php" data-i18n="nav.gadgets"></a></li>
                    <li><a href="review.php" data-i18n="nav.reviews"></a></li>
                    <li><a href="help.php" data-i18n="nav.help"></a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <input type="search" class="search-bar" data-i18n="[placeholder]searchPlaceholder">
                <div class="lang-switcher">
                    <button id="btn-en">EN</button>
                    <button id="btn-ja" class="active">JA</button>
                </div>
                <span><a href=cart.php>🛒</a></span> <span><a href=login.php>👤</a></span> 
            </div>
        </div>
    </header>


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
                    "nav": { "home": "HOME", "games": "GAMES", "gadgets": "GADGETS", "reviews": "REVIEWS", "help": "HELP" },
                    "searchPlaceholder": "Search..."
                }
            },
            ja: {
                translation: {
                    "pageTitle": "GG Store - エルデンリング",
                    "nav": { "home": "ホーム", "games": "ゲーム", "gadgets": "ガジェット", "reviews": "レビュー", "help": "ヘルプ" },
                    "searchPlaceholder": "検索..."
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