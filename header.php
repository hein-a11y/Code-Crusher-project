 <?php
    if(session_status()==PHP_SESSION_NONE){
        session_start();
    }
?> 
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

            position: sticky;
            top: 0;
            
        }      

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
            
        }

        .logo2 {
            text-decoration: none;
            color: #00bfff; 
            } 

        .logo2:hover {
            color: #00bfff; 
        }

        .logo2:visited {
            color: #00bfff; 
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

        

        .search-container {
            display: flex;
            align-items: center;
            overflow: hidden;
            border-radius: 3px;
            background-color:
        }

        
        span a{
            text-decoration: none;
        }
       

        .search-button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 45px;
            border: none;
            border-radius: 0 3px 3px 0;
            background-color: #2589d0;
            cursor: pointer;
        }

        .search-button::after {
            width: 24px;
            height: 24px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z' fill='%23fff'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            content: '';
        }

        .lang-switcher button:hover, .lang-switcher button.active {
            background-color: var(--primary-color);
            color: var(--background-color);
        }

        .search-bar {
            width: 250px;
            height: 45px;
            padding: 5px 15px;
            border: none;
            border-radius: 3px 0 0 3px;
            box-sizing: border-box;
            background-color: #f2f2f2;
            font-size: 1em;
            outline: none;

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
        <div class="logo"><a class ="logo2"href="index.php">GG STORE</a></div>
        
        <div class="hamburger-menu">
            <div class="bar"></div><div class="bar"></div><div class="bar"></div>
        </div>

        <div class="nav-container">
            <nav>
                <ul>
                    <li><a href="index.php" data-i18n="nav.home"></a></li>
                    <li><a href="games.php" data-i18n="nav.games"></a></li>
                    <li><a href="GADGETS.php" data-i18n="nav.gadgets"></a></li>
                    <li><a href="review-input.php" data-i18n="nav.reviews"></a></li>
                    <li><a href="help-input.php" data-i18n="nav.help"></a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <div class="search-container">
                    <label>
                        <input type="text" id="searchInput" class="search-bar" data-i18n="[placeholder]searchPlaceholder">
                    </label>
                    <button id="searchButton" class="search-button" aria-label="検索"></button>
                </div>
                
                

                <div class="lang-switcher">
                    <button id="btn-en">EN</button>
                    <button id="btn-ja" class="active">JA</button>
                </div>
                <span><a href=cart-input.php>🛒</a></span> <span><a href=login-input.php>👤</a></span> 
            </div>
        </div>

         <script>
                    async function searchUsers() {
                        const keyword = document.getElementById('searchInput').value;
                        const resultsDiv = document.getElementById('results'); // 結果表示エリア
                        
                        // URLパラメータを作成
                        const params = new URLSearchParams({
                            keyword: keyword
                        });
                        
                        try {
                            // PHPファイル（search_users.php）にリクエスト
                            const response = await fetch(`../search.php?${params}`);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            
                            const result = await response.json(); // PHPからのJSON応答
                            
                            // PHPから返された 'status' に応じて処理を分岐
                            
                            switch (result.status) {
                                case 'redirect':
                                    // リダイレクト指示があった場合
                                    if (result.method === 'GET') {
                                        // GETならそのままページ遷移
                                        window.location.href = result.action;
                                    } else {
                                        // POSTならフォームを作成して送信
                                        const form = document.createElement('form');
                                        form.method = result.method;
                                        form.action = result.action;
                                        document.body.appendChild(form);
                                        form.submit();
                                    }
                                    break;
                                
                        case 'error':
                            // 'error' (キーワードなし等) メッセージをアラートで表示
                            alert(result.message);
                            break;

                        default:
                            // PHPが 'success' や予期しないstatusを返した場合
                            alert('予期しない応答がありました。');
                    }
                    
                } catch (error) {
                    // fetch失敗やJSONパース失敗の場合
                    alert(`エラー: ${error.message}`);
                }
                    }

                    // (ボタンクリックとEnterキーのイベントリスナーはそのまま)
                    document.getElementById('searchButton').addEventListener('click', searchUsers);
                    document.getElementById('searchInput').addEventListener('keydown', (event) => {
                        if (event.key === 'Enter') {
                            searchUsers();
                        }
                    });
                </script>
    </header>
    <script type="text/javascript" src="./js/home.js" defer></script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>

<script type="text/javascript">
       

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
   