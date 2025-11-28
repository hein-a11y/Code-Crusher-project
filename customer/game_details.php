<?php
require_once '../header.php'; 
 require '../functions.php'; 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam-Style Store Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        appBg: '#1b2838', 
                        contentBg: '#202326', 
                        sidebarBg: 'rgba(0, 0, 0, 0.2)',
                        itemHover: '#2a475e',
                        accentBlue: '#66c0f4',
                        accentGreen: '#a4d007', 
                        tagBg: '#383b3d',
                        textMain: '#c6d4df',
                        textMuted: '#556772',
                        priceStrike: '#738895'
                    },
                    fontFamily: {
                        sans: ['Arial', 'Helvetica', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #1b2838;
            color: #c6d4df;
            font-family: Arial, Helvetica, sans-serif;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            background: #1b2838;
        }
        ::-webkit-scrollbar-thumb {
            background: #417a9b; 
            border-radius: 6px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #66c0f4; 
        }

        .game-row {
            background: rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease;
        }
        
        .game-row:hover {
            background: #2a475e; 
        }

        /* Pagination Buttons */
        .page-btn {
            background-color: #2a475e; /* Inactive Blue */
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .page-btn:hover:not(:disabled) {
            background-color: #66c0f4; /* Hover Light Blue */
            color: black;
        }
        .page-btn.active {
            background-color: #66c0f4; /* Active Light Blue */
            color: black;
            font-weight: bold;
            pointer-events: none;
        }
        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #1b2838;
        }

        /* Checkbox styling */
        input[type="checkbox"] {
            appearance: none;
            width: 16px;
            height: 16px;
            background-color: #1f2126;
            border-radius: 2px;
            display: grid;
            place-content: center;
            cursor: pointer;
        }
        input[type="checkbox"]::before {
            content: "";
            width: 10px;
            height: 10px;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #66c0f4; 
            transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        input[type="checkbox"]:checked::before {
            transform: scale(1);
        }
        
        /* Radio styling */
        input[type="radio"] {
            appearance: none;
            width: 16px;
            height: 16px;
            background-color: #1f2126;
            border-radius: 50%;
            display: grid;
            place-content: center;
            cursor: pointer;
        }
        input[type="radio"]::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em #66c0f4;
        }
        input[type="radio"]:checked::before {
            transform: scale(1);
        }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden">

    <!-- Top Navigation (Mock)
    <header class="h-16 bg-[#171a21] flex items-center px-6 shadow-lg z-20 shrink-0">
        <div class="text-2xl font-bold text-white tracking-widest mr-8" data-i18n="store">STORE</div>
        <nav class="hidden md:flex gap-6 text-sm font-bold text-[#b8b6b4]">
            <a href="#" class="hover:text-white text-[#1a9fff] border-b-2 border-[#1a9fff] pb-4 mt-4" data-i18n="your_store">Your Store</a>
            <a href="#" class="hover:text-white pb-4 mt-4" data-i18n="new_noteworthy">New & Noteworthy</a>
            <a href="#" class="hover:text-white pb-4 mt-4" data-i18n="categories">Categories</a>
            <a href="#" class="hover:text-white pb-4 mt-4" data-i18n="points_shop">Points Shop</a>
            <a href="#" class="hover:text-white pb-4 mt-4" data-i18n="news">News</a>
            <a href="#" class="hover:text-white pb-4 mt-4" data-i18n="labs">Labs</a>
        </nav>
        <div class="ml-auto flex items-center gap-4">
            <input type="text" placeholder="search" class="bg-[#316282] border border-[#316282] rounded px-3 py-1 text-sm text-white placeholder-blue-200 outline-none focus:bg-white focus:text-black transition-colors hidden sm:block">
            <div class="w-8 h-8 bg-[#316282] text-white flex items-center justify-center rounded cursor-pointer hover:bg-[#66c0f4]">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </header> -->

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden relative">
        
        <!-- Sidebar (Filters) -->
        <aside class="w-64 bg-[#101319] overflow-y-auto hidden md:block shrink-0 p-4 text-sm font-sans">
            
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-white font-medium" data-i18n="filters">Filters</h3>
                    <button class="text-[10px] text-[#66c0f4] hover:text-white" onclick="resetFilters()" data-i18n="reset">Reset</button>
                </div>
                <div class="bg-[#1f2126] border border-[#43464d] rounded p-2 flex items-center">
                    <i class="fas fa-search text-gray-500 mr-2"></i>
                    <input type="text" placeholder="Tags or Keywords" class="bg-transparent border-none outline-none text-white w-full text-xs" data-i18n-placeholder="search_tags">
                </div>
            </div>

            <!-- Filter Section: Genre -->
            <div class="mb-4 border-t border-[#2a2d33] pt-2">
                <div class="flex justify-between items-center text-[#c6d4df] uppercase text-xs font-bold mb-2 cursor-pointer hover:text-white" onclick="toggleSection('genre-options', this)">
                    <span data-i18n="genre">Genre</span>
                    <i class="fas fa-chevron-down transition-transform duration-200"></i>
                </div>
                <div id="genre-options" class="space-y-1 pl-2 transition-all">
                    <!-- Checkboxes generated by JS -->
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" value="Action" onchange="filterGames()">
                        <span class="text-[#969696] group-hover:text-white text-xs" data-i18n="Action">Action</span>
                        <span id="count-Action" class="text-[#556772] text-[10px] ml-auto">0</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" value="Adventure" onchange="filterGames()">
                        <span class="text-[#969696] group-hover:text-white text-xs" data-i18n="Adventure">Adventure</span>
                        <span id="count-Adventure" class="text-[#556772] text-[10px] ml-auto">0</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" value="RPG" onchange="filterGames()">
                        <span class="text-[#969696] group-hover:text-white text-xs" data-i18n="RPG">RPG</span>
                        <span id="count-RPG" class="text-[#556772] text-[10px] ml-auto">0</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" value="Strategy" onchange="filterGames()">
                        <span class="text-[#969696] group-hover:text-white text-xs" data-i18n="Strategy">Strategy</span>
                        <span id="count-Strategy" class="text-[#556772] text-[10px] ml-auto">0</span>
                    </label>
                     <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" value="Simulation" onchange="filterGames()">
                        <span class="text-[#969696] group-hover:text-white text-xs" data-i18n="Simulation">Simulation</span>
                        <span id="count-Simulation" class="text-[#556772] text-[10px] ml-auto">0</span>
                    </label>
                </div>
            </div>

             <!-- Filter Section: Platform -->
            <div class="mb-4 border-t border-[#2a2d33] pt-2">
                <div class="flex justify-between items-center text-[#c6d4df] uppercase text-xs font-bold mb-2 cursor-pointer hover:text-white">
                    <span data-i18n="platform">Platform</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                 <div class="space-y-1 pl-2">
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" checked class="accent-[#32353c] w-4 h-4 bg-[#1f2126]">
                        <span class="text-[#969696] group-hover:text-white text-xs">Windows</span>
                    </label>
                     <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="checkbox" class="accent-[#32353c] w-4 h-4 bg-[#1f2126]">
                        <span class="text-[#969696] group-hover:text-white text-xs">macOS</span>
                    </label>
                </div>
            </div>
            
             <!-- Filter Section: Language (Active) -->
            <div class="mb-4 border-t border-[#2a2d33] pt-2">
                <div class="flex justify-between items-center text-[#c6d4df] uppercase text-xs font-bold mb-2 cursor-pointer hover:text-white" onclick="toggleSection('lang-options', this)">
                    <span data-i18n="language">Language</span>
                    <i class="fas fa-chevron-down text-[#556772]"></i>
                </div>
                <div id="lang-options" class="space-y-1 pl-2">
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="radio" name="lang" value="en" checked onchange="setLanguage('en')">
                        <span class="text-[#969696] group-hover:text-white text-xs">English</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                        <input type="radio" name="lang" value="ja" onchange="setLanguage('ja')">
                        <span class="text-[#969696] group-hover:text-white text-xs">日本語 (Japanese)</span>
                    </label>
                </div>
            </div>

        </aside>

        <!-- Main Content (Game List) -->
        <main class="flex-1 overflow-y-auto bg-[#1b2838] p-4 lg:p-6" id="main-scroll">
            
            <!-- List Header/Tabs -->
            <div class="bg-[#101319] p-1 flex justify-between items-center mb-4 sticky top-0 z-10 shadow-md">
                <div class="flex text-sm overflow-x-auto">
                    <button class="px-4 py-2 text-white bg-[#31363d] font-medium rounded-sm whitespace-nowrap" data-i18n="all">All</button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap" data-i18n="top_sellers">Top Sellers</button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap" data-i18n="new_releases">New Releases</button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap" data-i18n="upcoming">Upcoming</button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap" data-i18n="discounted">Discounted</button>
                </div>
            </div>

            <!-- Search Results Info -->
            <div class="text-[#556772] text-xs mb-2">
                <span id="results-count">0</span> <span data-i18n="results_match">results matching your search</span>
            </div>

            <!-- Game List Container -->
            <div id="games-list" class="flex flex-col gap-[2px]">
                <!-- Javascript will inject 100 rows per page here -->
            </div>
            
            <!-- Dynamic Pagination Container -->
            <div id="pagination-container" class="mt-6 flex justify-center gap-2 text-sm flex-wrap pb-6">
                <!-- Javascript will inject buttons 1-12 here -->
            </div>
        </main>
    </div>

    <script>
        // --- CONFIGURATION ---
        const ITEMS_PER_PAGE = 100;
        let currentPage = 1;
        let allGames = [];
        let currentFilteredGames = [];
        let currentLang = 'en';

        // --- TRANSLATION DATA ---
        const translations = {
            en: {
                store: "STORE", your_store: "Your Store", new_noteworthy: "New & Noteworthy", categories: "Categories",
                points_shop: "Points Shop", news: "News", labs: "Labs", filters: "Filters", reset: "Reset",
                search_tags: "Tags or Keywords", genre: "Genre", features: "Features", platform: "Platform",
                language: "Language", all: "All", top_sellers: "Top Sellers", new_releases: "New Releases",
                upcoming: "Upcoming", discounted: "Discounted", display: "Display:", results_match: "results matching your search",
                Action: "Action", Adventure: "Adventure", RPG: "RPG", Strategy: "Strategy", Simulation: "Simulation",
                "Overwhelmingly Positive": "Overwhelmingly Positive", "Very Positive": "Very Positive",
                "Mostly Positive": "Mostly Positive", "Mixed": "Mixed", "Mostly Negative": "Mostly Negative",
                "user_reviews": "user reviews"
            },
            ja: {
                store: "ストア", your_store: "あなたのストア", new_noteworthy: "新作＆注目作", categories: "カテゴリ",
                points_shop: "ポイントショップ", news: "ニュース", labs: "ラボ", filters: "絞り込み", reset: "リセット",
                search_tags: "タグまたはキーワード", genre: "ジャンル", features: "機能", platform: "プラットフォーム",
                language: "言語", all: "すべて", top_sellers: "売り上げ上位", new_releases: "新作",
                upcoming: "近日登場", discounted: "スペシャル", display: "表示:", results_match: "件の検索結果",
                Action: "アクション", Adventure: "アドベンチャー", RPG: "RPG", Strategy: "ストラテジー", Simulation: "シミュレーション",
                "Overwhelmingly Positive": "圧倒的に好評", "Very Positive": "非常に好評",
                "Mostly Positive": "好評", "Mixed": "賛否両論", "Mostly Negative": "不評",
                "user_reviews": "件のユーザーレビュー"
            }
        };

        // --- DATA GENERATION (1200 Games) ---
        const gameTitles = [
            "Escape from Tarkov", "Mount & Blade II: Bannerlord", "Euro Truck Simulator 2", "Stardew Valley", 
            "Elden Ring", "Cyberpunk 2077", "Hades II", "Baldur's Gate 3", "Factorio", "RimWorld",
            "Rust", "Terraria", "The Witcher 3: Wild Hunt", "Red Dead Redemption 2", "God of War",
            "Counter-Strike 2", "Dota 2", "Apex Legends", "PUBG: BATTLEGROUNDS", "Destiny 2",
            "Valheim", "Hollow Knight", "Celeste", "Subnautica", "No Man's Sky",
            "Project Zomboid", "Kenshi", "Satisfactory", "Deep Rock Galactic", "Sea of Thieves",
            "Warframe", "Path of Exile", "Lost Ark", "Black Desert", "Final Fantasy XIV",
            "Monster Hunter: World", "Resident Evil 4", "Dark Souls III", "Sekiro: Shadows Die Twice", "Bloodborne",
            "Civilization VI", "XCOM 2", "Stellaris", "Crusader Kings III", "Hearts of Iron IV",
            "Cities: Skylines II", "Planet Zoo", "Frostpunk 2", "Manor Lords", "Against the Storm"
        ];
        
        const tagsList = ["Action", "Adventure", "RPG", "Strategy", "Simulation", "FPS", "Survival", "Open World", "Indie", "Multiplayer", "Co-op", "Sci-fi", "Fantasy", "Horror"];

        const reviewTypes = ["Overwhelmingly Positive", "Very Positive", "Mostly Positive", "Mixed", "Mostly Negative"];
        const reviewColors = {
            "Overwhelmingly Positive": "text-[#66c0f4]",
            "Very Positive": "text-[#66c0f4]",
            "Mostly Positive": "text-[#66c0f4]",
            "Mixed": "text-[#b9a074]",
            "Mostly Negative": "text-[#a34c26]"
        };

        function initGames() {
            // GENERATE 1200 ITEMS for 12 Pages of 100
            for (let i = 0; i < 1200; i++) {
                let title = gameTitles[i % gameTitles.length];
                // Append number to distinct duplicates
                title += ` ${Math.floor(i / gameTitles.length) + 1}`;
                
                const today = new Date();
                const date = new Date(today.getTime() + (Math.random() * 10000000000 * (Math.random() > 0.8 ? 1 : -1)));
                
                // Distribute genres evenly to ensure filtering works well across 1200 items
                const mainTags = ["Action", "Adventure", "RPG", "Strategy", "Simulation"];
                const primaryTag = mainTags[i % mainTags.length]; 
                
                const gameTags = [primaryTag];
                while(gameTags.length < 4) {
                    const t = tagsList[Math.floor(Math.random() * tagsList.length)];
                    if(!gameTags.includes(t)) gameTags.push(t);
                }

                const reviewText = reviewTypes[Math.floor(Math.random() * reviewTypes.length)];
                const reviewCount = Math.floor(Math.random() * 50000) + 500;
                const basePrice = Math.floor(Math.random() * 60) + 10;
                const hasDiscount = Math.random() > 0.4;
                const discountPercent = hasDiscount ? [10, 15, 20, 25, 33, 40, 50, 60, 75, 80, 90][Math.floor(Math.random() * 11)] : 0;
                const finalPrice = hasDiscount ? (basePrice * (1 - discountPercent/100)).toFixed(2) : basePrice.toFixed(2);
                
                const imgUrl = `https://picsum.photos/seed/${i + 'steam1200'}/240/112`;

                allGames.push({
                    id: i,
                    title,
                    date,
                    tags: gameTags,
                    reviewText,
                    reviewColor: reviewColors[reviewText],
                    reviewCount,
                    basePrice,
                    finalPrice,
                    hasDiscount,
                    discountPercent,
                    imgUrl
                });
            }
            updateTagCounts();
            currentFilteredGames = [...allGames]; // Default view
        }

        function updateTagCounts() {
            const counts = { Action: 0, Adventure: 0, RPG: 0, Strategy: 0, Simulation: 0 };
            allGames.forEach(game => {
                game.tags.forEach(tag => {
                    if (counts.hasOwnProperty(tag)) {
                        counts[tag]++;
                    }
                });
            });
            
            for (const [tag, count] of Object.entries(counts)) {
                const el = document.getElementById(`count-${tag}`);
                if(el) el.innerText = count.toLocaleString();
            }
        }

        // --- CORE LOGIC: FILTER, PAGINATE, RENDER ---

        function filterGames() {
            // 1. Get Filters
            const checkboxes = document.querySelectorAll('#genre-options input[type="checkbox"]:checked');
            const selectedGenres = Array.from(checkboxes).map(cb => cb.value);

            // 2. Filter Master Array
            if (selectedGenres.length === 0) {
                currentFilteredGames = [...allGames];
            } else {
                currentFilteredGames = allGames.filter(game => 
                    selectedGenres.some(genre => game.tags.includes(genre))
                );
            }

            // 3. Reset to Page 1 on new filter
            currentPage = 1;

            // 4. Update UI
            updateUI();
        }

        function changePage(newPage) {
            const totalPages = Math.ceil(currentFilteredGames.length / ITEMS_PER_PAGE);
            if(newPage < 1 || newPage > totalPages) return;
            
            currentPage = newPage;
            updateUI();
            // Scroll to top of list
            document.getElementById('main-scroll').scrollTop = 0;
        }

        function updateUI() {
            // Update counts
            document.getElementById('results-count').innerText = currentFilteredGames.length.toLocaleString();

            // Slicing for Pagination
            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;
            const gamesToShow = currentFilteredGames.slice(start, end);

            // Render List
            renderList(gamesToShow);

            // Render Pagination Buttons
            renderPagination();
        }

        function renderPagination() {
            const container = document.getElementById('pagination-container');
            container.innerHTML = '';
            
            const totalPages = Math.ceil(currentFilteredGames.length / ITEMS_PER_PAGE);
            
            if (totalPages <= 1) return; // Hide if only 1 page

            // Prev Button
            const prevBtn = document.createElement('button');
            prevBtn.innerText = '<';
            prevBtn.className = 'page-btn';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => changePage(currentPage - 1);
            container.appendChild(prevBtn);

            // Page Numbers
            for(let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.onclick = () => changePage(i);
                container.appendChild(btn);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.innerText = '>';
            nextBtn.className = 'page-btn';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => changePage(currentPage + 1);
            container.appendChild(nextBtn);
        }

        function renderList(games) {
            const listContainer = document.getElementById('games-list');
            
            if (games.length === 0) {
                listContainer.innerHTML = `<div class="p-8 text-center text-[#556772]">No games match your filters.</div>`;
                return;
            }

            let html = '';
            games.forEach(game => {
                let priceBlock = '';
                if (game.hasDiscount) {
                    priceBlock = `
                        <div class="flex items-center gap-2 bg-[#00000033] pr-1 pl-0">
                            <div class="bg-[#4c6b22] text-[#a4d007] font-bold px-1.5 py-1 text-sm">-${game.discountPercent}%</div>
                            <div class="flex flex-col items-end justify-center leading-none pr-1">
                                <div class="text-[#738895] text-[11px] line-through decoration-[1px] relative top-[1px]">¥${(game.basePrice * 100).toLocaleString()}</div>
                                <div class="text-[#beee11] text-xs font-medium">¥${(Math.floor(game.finalPrice * 100)).toLocaleString()}</div>
                            </div>
                        </div>
                    `;
                } else {
                    priceBlock = `<div class="text-white text-xs px-2">¥${(game.basePrice * 100).toLocaleString()}</div>`;
                }

                const translatedReview = translations[currentLang][game.reviewText] || game.reviewText;
                const reviewSuffix = translations[currentLang]["user_reviews"];

                html += `
                    <div class="game-row flex h-[69px] md:h-[90px] w-full cursor-pointer group px-2 md:px-0">
                        <div class="w-[120px] md:w-[184px] shrink-0 p-1 md:p-[5px]">
                            <img src="${game.imgUrl}" alt="${game.title}" class="w-full h-full object-cover shadow-sm group-hover:shadow-md">
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-between py-2 md:py-3 pr-4 overflow-hidden">
                            <div class="flex justify-between items-start">
                                <div class="font-medium text-[#c6d4df] group-hover:text-white text-sm md:text-base truncate pr-2">${game.title}</div>
                            </div>
                            
                            <div class="flex items-center gap-2 text-xs text-[#556772]">
                                <i class="fab fa-windows text-[#556772] text-xs hover:text-white"></i>
                                <span class="hidden md:inline-block text-[#556772] group-hover:text-[#91aebf]">
                                    ${game.date.toLocaleDateString(currentLang === 'ja' ? 'ja-JP' : 'en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
                                </span>
                                <div class="hidden lg:flex gap-1 ml-2">
                                    ${game.tags.slice(0, 4).map(t => {
                                        const translatedTag = translations[currentLang][t] || t;
                                        return `<span class="bg-[#383b3d] text-[#b8b6b4] px-1 rounded-[2px] text-[10px] group-hover:bg-[#5b646d] group-hover:text-white transition-colors">${translatedTag}</span>`;
                                    }).join('')}
                                </div>
                            </div>
                            
                            <div class="text-[10px] md:text-xs truncate hidden sm:block">
                                <span class="${game.reviewColor} hover:underline cursor-pointer">${translatedReview}</span>
                                <span class="text-[#556772]"> (${game.reviewCount.toLocaleString()} ${reviewSuffix})</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end w-[120px] shrink-0 pr-4">
                            ${priceBlock}
                        </div>
                    </div>
                `;
            });
            listContainer.innerHTML = html;
        }

        // --- HELPER FUNCTIONS ---
        function resetFilters() {
            const checkboxes = document.querySelectorAll('#genre-options input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            filterGames();
        }

        function setLanguage(lang) {
            currentLang = lang;
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (translations[lang][key]) el.innerText = translations[lang][key];
            });
            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (translations[lang][key]) el.placeholder = translations[lang][key];
            });
            updateUI(); // Re-render to update dynamic translations
        }

        function toggleSection(id, header) {
            const el = document.getElementById(id);
            const icon = header.querySelector('.fa-chevron-down') || header.querySelector('.fa-chevron-right');
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                if(icon) { icon.classList.remove('fa-chevron-right'); icon.classList.add('fa-chevron-down'); }
            }
        }

        // --- INIT ---
        initGames();
        updateUI(); // This renders the first 100 items + pagination

    </script>
</body>
</html>
