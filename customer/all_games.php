<?php
session_start();
// --- 1. SETUP & INCLUDES ---
if (file_exists('../header.php')) {
    require_once '../header.php';
}
if (file_exists('../functions.php')) {
    require '../functions.php';
}

   $pdo=null;

   if($pdo == null){
    $pdo = getPDO();
   }
   
   $sql=$pdo->query("SELECT * FROM gg_game WHERE Sales_Status = 1");

   $games = $sql->fetchAll();
 
   
   


// --- 2. CONFIGURATION & STATE ---
// Set a fixed seed so the "random" games stay the same across page reloads
mt_srand(54321);

$itemsPerPage = 100;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$currentLang = isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ja']) ? $_GET['lang'] : 'en';
$selectedGenres = isset($_GET['genres']) && is_array($_GET['genres']) ? $_GET['genres'] : [];

// --- 3. TRANSLATIONS ---
$translations = [
    'en' => [
        'store' => "STORE", 'your_store' => "Your Store", 'new_noteworthy' => "New & Noteworthy", 'categories' => "Categories",
        'points_shop' => "Points Shop", 'news' => "News", 'labs' => "Labs", 'filters' => "Filters", 'reset' => "Reset",
        'search_tags' => "Tags or Keywords", 'genre' => "Genre", 'features' => "Features", 'platform' => "Platform",
        'language' => "Language", 'all' => "All", 'top_sellers' => "Top Sellers", 'new_releases' => "New Releases",
        'upcoming' => "Upcoming", 'discounted' => "Discounted", 'display' => "Display:", 'results_match' => "results matching your search",
        'Action' => "Action", 'Adventure' => "Adventure", 'RPG' => "RPG", 'Strategy' => "Strategy", 'Simulation' => "Simulation",
        'Overwhelmingly Positive' => "Overwhelmingly Positive", 'Very Positive' => "Very Positive",
        'Mostly Positive' => "Mostly Positive", 'Mixed' => "Mixed", 'Mostly Negative' => "Mostly Negative",
        'user_reviews' => "user reviews"
    ],
    'ja' => [
        'store' => "ストア", 'your_store' => "あなたのストア", 'new_noteworthy' => "新作＆注目作", 'categories' => "カテゴリ",
        'points_shop' => "ポイントショップ", 'news' => "ニュース", 'labs' => "ラボ", 'filters' => "絞り込み", 'reset' => "リセット",
        'search_tags' => "タグまたはキーワード", 'genre' => "ジャンル", 'features' => "機能", 'platform' => "プラットフォーム",
        'language' => "言語", 'all' => "すべて", 'top_sellers' => "売り上げ上位", 'new_releases' => "新作",
        'upcoming' => "近日登場", 'discounted' => "スペシャル", 'display' => "表示:", 'results_match' => "件の検索結果",
        'Action' => "アクション", 'Adventure' => "アドベンチャー", 'RPG' => "RPG", 'Strategy' => "ストラテジー", 'Simulation' => "シミュレーション",
        'Overwhelmingly Positive' => "圧倒的に好評", 'Very Positive' => "非常に好評",
        'Mostly Positive' => "好評", 'Mixed' => "賛否両論", 'Mostly Negative' => "不評",
        'user_reviews' => "件のユーザーレビュー"
    ]
];

function t($key) {
    global $translations, $currentLang;
    return $translations[$currentLang][$key] ?? $key;
}

// --- 4. DATA GENERATION (1200 Games) ---
$gameTitles = [];

for($i=0;$i<count($games);$i++){
    $gameTitles[$i] = $games[$i]['game_name'];

    
   
}

// $tagsList = ["Action", "Adventure", "RPG", "Strategy", "Simulation", "FPS", "Survival", "Open World", "Indie", "Multiplayer", "Co-op", "Sci-fi", "Fantasy", "Horror"];
$reviewTypes = ["Overwhelmingly Positive", "Very Positive", "Mostly Positive", "Mixed", "Mostly Negative"];
$reviewColors = [
    "Overwhelmingly Positive" => "text-[#66c0f4]",
    "Very Positive" => "text-[#66c0f4]",
    "Mostly Positive" => "text-[#66c0f4]",
    "Mixed" => "text-[#b9a074]",
    "Mostly Negative" => "text-[#a34c26]"
];

$allGames = [];
// Generate 1200 games
for ($i = 0; $i < 500; $i++) {
    $titleIndex = $i % count($gameTitles);
    $title = $gameTitles[$titleIndex];
    if ($i >= count($gameTitles)) {
        $title .= " " . (floor($i / count($gameTitles)) + 1);
    }

    // Random Date within roughly +/- 6 months
    $timestamp = time() + (mt_rand(-15000000, 15000000));
    
    // Formatting date based on lang
    if ($currentLang === 'ja') {
        $dateStr = date('Y年n月j日', $timestamp);
    } else {
        $dateStr = date('M j, Y', $timestamp);
    }

    // Tags
    $genre = $pdo->query('SELECT genre_name FROM gg_genres ');
    $mainTags = $genre->fetchAll();
    $gameTags = [];
    // $primaryTag = $mainTags[$i % count($mainTags)];
    // $gameTags = [$primaryTag];
    
    // Add 3 random tags

    $sql_genres = $pdo->prepare('SELECT game_gen.game_id,gen.genre_name FROM gg_game_genres AS game_gen INNER JOIN gg_genres AS gen ON game_gen.genre_id = gen.genre_id WHERE game_gen.game_id = ?');
    $sql_genres->execute([$i]);

    foreach ($sql_genres as $genres) {
        $genres_name = $genres['genre_name'];
        $gameTags[] = $genres_name;
    }
   

    $reviewText = $reviewTypes[mt_rand(0, count($reviewTypes) - 1)];
    $reviewCount = mt_rand(500, 50500);
    $basePrice = mt_rand(10, 70);
    $hasDiscount = (mt_rand(0, 100) > 60); // 40% chance
    $discountPercent = $hasDiscount ? [10, 15, 20, 25, 33, 40, 50, 60, 75, 80, 90][mt_rand(0, 10)] : 0;
    $finalPrice = $hasDiscount ? ($basePrice * (1 - $discountPercent/100)) : $basePrice;

    $allGames[] = [
        'id' => $i,
        'title' => $title,
        'date' => $dateStr,
        'tags' => $gameTags,
        'reviewText' => $reviewText,
        'reviewColor' => $reviewColors[$reviewText],
        'reviewCount' => $reviewCount,
        'basePrice' => $basePrice,
        'finalPrice' => number_format($finalPrice, 2),
        'hasDiscount' => $hasDiscount,
        'discountPercent' => $discountPercent,
        'imgUrl' => "https://picsum.photos/seed/" . ($i . 'steam1200') . "/240/112"
    ];
}

// // --- 5. FILTERING & COUNTS ---
// // Calculate counts BEFORE filtering for the sidebar badges
// $tagCounts = array_fill_keys(["Action", "Adventure", "RPG", "Strategy", "Simulation"], 0);
// foreach ($allGames as $g) {
//     foreach ($g['tags'] as $t) {
//         if (isset($tagCounts[$t])) $tagCounts[$t]++;
//         debug($tagCounts);
//     }
// }

// Filter the Games
$filteredGames = [];
if (empty($selectedGenres)) {
    $filteredGames = $allGames;
} else {
    foreach ($allGames as $game) {
        // Check if game has ANY of the selected genres (OR logic)
        $matches = array_intersect($game['tags'], $selectedGenres);
        if (!empty($matches)) {
            $filteredGames[] = $game;
        }
    }
}

// --- 6. PAGINATION ---
$totalFiltered = count($filteredGames);
$totalPages = ceil($totalFiltered / $itemsPerPage);
// Ensure current page is valid
if ($currentPage > $totalPages) $currentPage = $totalPages;
if ($currentPage < 1) $currentPage = 1;

$offset = ($currentPage - 1) * $itemsPerPage;
$displayGames = array_slice($filteredGames, $offset, $itemsPerPage);

// Helper to build URL with current params
function buildUrl($newPage = null, $newLang = null) {
    global $currentPage, $currentLang;
    $params = $_GET;
    if ($newPage !== null) $params['page'] = $newPage;
    if ($newLang !== null) $params['lang'] = $newLang;
    return '?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
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
        body { background-color: #1b2838; color: #c6d4df; font-family: Arial, Helvetica, sans-serif; }
        ::-webkit-scrollbar { width: 10px; background: #1b2838; }
        ::-webkit-scrollbar-thumb { background: #417a9b; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #66c0f4; }
        .game-row { background: rgba(0, 0, 0, 0.2); transition: all 0.2s ease; }
        .game-row:hover { background: #2a475e; }
        .page-btn { background-color: #2a475e; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; transition: all 0.2s; }
        .page-btn:hover:not(:disabled) { background-color: #66c0f4; color: black; }
        .page-btn.active { background-color: #66c0f4; color: black; font-weight: bold; pointer-events: none; }
        .page-btn:disabled { opacity: 0.5; cursor: not-allowed; background-color: #1b2838; }
        input[type="checkbox"], input[type="radio"] { accent-color: #66c0f4; cursor: pointer; }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden">

    <!-- Top Navigation -->
    <header class="h-16 bg-[#171a21] flex items-center px-6 shadow-lg z-20 shrink-0">
        <div class="text-2xl font-bold text-white tracking-widest mr-8"><?php echo t('store'); ?></div>
        <nav class="hidden md:flex gap-6 text-sm font-bold text-[#b8b6b4]">
            <a href="#" class="hover:text-white text-[#1a9fff] border-b-2 border-[#1a9fff] pb-4 mt-4"><?php echo t('your_store'); ?></a>
            <a href="#" class="hover:text-white pb-4 mt-4"><?php echo t('new_noteworthy'); ?></a>
            <a href="#" class="hover:text-white pb-4 mt-4"><?php echo t('categories'); ?></a>
            <a href="#" class="hover:text-white pb-4 mt-4"><?php echo t('points_shop'); ?></a>
            <a href="#" class="hover:text-white pb-4 mt-4"><?php echo t('news'); ?></a>
            <a href="#" class="hover:text-white pb-4 mt-4"><?php echo t('labs'); ?></a>
        </nav>
        <div class="ml-auto flex items-center gap-4">
            <input type="text" placeholder="search" class="bg-[#316282] border border-[#316282] rounded px-3 py-1 text-sm text-white placeholder-blue-200 outline-none focus:bg-white focus:text-black transition-colors hidden sm:block">
            <div class="w-8 h-8 bg-[#316282] text-white flex items-center justify-center rounded cursor-pointer hover:bg-[#66c0f4]">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden relative">
        
        <!-- Sidebar (Filters wrapped in Form) -->
        <aside class="w-64 bg-[#101319] overflow-y-auto hidden md:block shrink-0 p-4 text-sm font-sans">
            <form id="filterForm" method="GET" action="">
                <!-- Preserve page reset on filter change -->
                <input type="hidden" name="page" value="1"> 
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-white font-medium"><?php echo t('filters'); ?></h3>
                        <a href="?lang=<?php echo $currentLang; ?>" class="text-[10px] text-[#66c0f4] hover:text-white"><?php echo t('reset'); ?></a>
                    </div>
                    <div class="bg-[#1f2126] border border-[#43464d] rounded p-2 flex items-center">
                        <i class="fas fa-search text-gray-500 mr-2"></i>
                        <input type="text" placeholder="<?php echo t('search_tags'); ?>" class="bg-transparent border-none outline-none text-white w-full text-xs">
                    </div>
                </div>

                <!-- Genre Filter -->
                <div class="mb-4 border-t border-[#2a2d33] pt-2">
                    <div class="flex justify-between items-center text-[#c6d4df] uppercase text-xs font-bold mb-2 cursor-pointer hover:text-white" onclick="toggleSection('genre-options')">
                        <span><?php echo t('genre'); ?></span>
                        <i class="fas fa-chevron-down transition-transform duration-200"></i>
                    </div>
                    <div id="genre-options" class="space-y-1 pl-2 transition-all">
                        <?php 
                        $genres = ['Action', 'Adventure', 'RPG', 'Strategy', 'Simulation'];
                        foreach($genres as $g): 
                            $isChecked = in_array($g, $selectedGenres) ? 'checked' : '';
                        ?>
                        <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                            <input type="checkbox" name="genres[]" value="<?php echo $g; ?>" <?php echo $isChecked; ?> onchange="this.form.submit()" class="bg-[#1f2126] border-gray-600">
                            <span class="text-[#969696] group-hover:text-white text-xs"><?php echo t($g); ?></span>
                            <span class="text-[#556772] text-[10px] ml-auto"><?php echo number_format($mainTags[$g]); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Platform Filter (Mock) -->
                <div class="mb-4 border-t border-[#2a2d33] pt-2">
                    <div class="flex justify-between items-center text-[#c6d4df] uppercase text-xs font-bold mb-2 cursor-pointer hover:text-white">
                        <span><?php echo t('platform'); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                     <div class="space-y-1 pl-2">
                        <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                            <input type="checkbox" checked disabled class="bg-[#1f2126]">
                            <span class="text-[#969696] group-hover:text-white text-xs">Windows</span>
                        </label>
                         <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                            <input type="checkbox" class="bg-[#1f2126]">
                            <span class="text-[#969696] group-hover:text-white text-xs">macOS</span>
                        </label>
                    </div>
                </div>
                
                <!-- Language Filter -->
                <div class="mb-4 border-t border-[#2a2d33] pt-2">
                    <div class="flex justify-between items-center text-[#c6d4df] uppercase text-xs font-bold mb-2 cursor-pointer hover:text-white" onclick="toggleSection('lang-options')">
                        <span><?php echo t('language'); ?></span>
                        <i class="fas fa-chevron-down text-[#556772]"></i>
                    </div>
                    <div id="lang-options" class="space-y-1 pl-2">
                        <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                            <input type="radio" name="lang" value="en" <?php echo $currentLang === 'en' ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span class="text-[#969696] group-hover:text-white text-xs">English</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer hover:text-white group">
                            <input type="radio" name="lang" value="ja" <?php echo $currentLang === 'ja' ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span class="text-[#969696] group-hover:text-white text-xs">日本語 (Japanese)</span>
                        </label>
                    </div>
                </div>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-[#1b2838] p-4 lg:p-6" id="main-scroll">
            
            <!-- List Header -->
            <div class="bg-[#101319] p-1 flex justify-between items-center mb-4">
                <div class="flex text-sm overflow-x-auto">
                    <button class="px-4 py-2 text-white bg-[#31363d] font-medium rounded-sm whitespace-nowrap"><?php echo t('all'); ?></button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap"><?php echo t('top_sellers'); ?></button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap"><?php echo t('new_releases'); ?></button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap"><?php echo t('upcoming'); ?></button>
                    <button class="px-4 py-2 text-[#c6d4df] hover:text-white hover:bg-[#31363d]/50 transition-colors whitespace-nowrap"><?php echo t('discounted'); ?></button>
                </div>
            </div>

            <!-- Results Info -->
            <div class="text-[#556772] text-xs mb-2">
                <span><?php echo number_format($totalFiltered); ?></span> <span><?php echo t('results_match'); ?></span>
            </div>

            <!-- Game List (PHP Loop) -->
            <div id="games-list" class="flex flex-col gap-[2px]">
                <?php if(empty($displayGames)): ?>
                    <div class="p-8 text-center text-[#556772]">No games match your filters.</div>
                <?php else: ?>
                    <?php foreach($displayGames as $game): ?>
                        <div class="game-row flex h-[69px] md:h-[90px] w-full cursor-pointer group px-2 md:px-0">
                            <!-- Image -->
                            <div class="w-[120px] md:w-[184px] shrink-0 p-1 md:p-[5px]">
                                <img src="<?php echo $game['imgUrl']; ?>" alt="<?php echo $game['title']; ?>" class="w-full h-full object-cover shadow-sm group-hover:shadow-md">
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 flex flex-col justify-between py-2 md:py-3 pr-4 overflow-hidden">
                                <div class="flex justify-between items-start">
                                    <div class="font-medium text-[#c6d4df] group-hover:text-white text-sm md:text-base truncate pr-2"><?php echo $game['title']; ?></div>
                                </div>
                                
                                <div class="flex items-center gap-2 text-xs text-[#556772]">
                                    <i class="fab fa-windows text-[#556772] text-xs hover:text-white"></i>
                                    <span class="hidden md:inline-block text-[#556772] group-hover:text-[#91aebf]"><?php echo $game['date']; ?></span>
                                    <div class="hidden lg:flex gap-1 ml-2">
                                        <?php 
                                        $displayTags = array_slice($game['tags'], 0, 4);
                                        foreach($displayTags as $t): 
                                        ?>
                                            <span class="bg-[#383b3d] text-[#b8b6b4] px-1 rounded-[2px] text-[10px] group-hover:bg-[#5b646d] group-hover:text-white transition-colors"><?php echo t($t); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <div class="text-[10px] md:text-xs truncate hidden sm:block">
                                    <span class="<?php echo $game['reviewColor']; ?> hover:underline cursor-pointer"><?php echo t($game['reviewText']); ?></span>
                                    <span class="text-[#556772]"> (<?php echo number_format($game['reviewCount']) . ' ' . t('user_reviews'); ?>)</span>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center justify-end w-[120px] shrink-0 pr-4">
                                <?php if($game['hasDiscount']): ?>
                                    <div class="flex items-center gap-2 bg-[#00000033] pr-1 pl-0">
                                        <div class="bg-[#4c6b22] text-[#a4d007] font-bold px-1.5 py-1 text-sm">-<?php echo $game['discountPercent']; ?>%</div>
                                        <div class="flex flex-col items-end justify-center leading-none pr-1">
                                            <div class="text-[#738895] text-[11px] line-through decoration-[1px] relative top-[1px]">¥<?php echo number_format($game['basePrice'] * 100); ?></div>
                                            <div class="text-[#beee11] text-xs font-medium">¥<?php echo number_format($game['finalPrice'] * 100); ?></div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-white text-xs px-2">¥<?php echo number_format($game['basePrice'] * 100); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Pagination (PHP Logic) -->
            <?php if($totalPages > 1): ?>
            <div class="mt-6 flex justify-center gap-2 text-sm flex-wrap pb-6">
                <!-- Prev Button -->
                <?php if($currentPage > 1): ?>
                    <a href="<?php echo buildUrl($currentPage - 1); ?>" class="page-btn text-white">&lt;</a>
                <?php else: ?>
                    <button disabled class="page-btn">&lt;</button>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?php echo buildUrl($i); ?>" class="page-btn <?php echo $i === $currentPage ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Button -->
                <?php if($currentPage < $totalPages): ?>
                    <a href="<?php echo buildUrl($currentPage + 1); ?>" class="page-btn text-white">&gt;</a>
                <?php else: ?>
                    <button disabled class="page-btn">&gt;</button>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </main>
    </div>

    <script>
        // Simple helper for expanding sidebar sections
        function toggleSection(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('hidden');
        }
    </script>
</body>
</html>