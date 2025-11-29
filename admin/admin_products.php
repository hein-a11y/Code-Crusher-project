<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<?php
// --- データベース接続 ---
$pdo = getPDO();

// --- マスタデータの取得 (フィルター用) ---
$platforms = $pdo->query("SELECT * FROM gg_platforms ORDER BY platform_id ASC")->fetchAll(PDO::FETCH_ASSOC);
$genres = $pdo->query("SELECT * FROM gg_genres ORDER BY genre_id ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM gg_category ORDER BY category_id ASC")->fetchAll(PDO::FETCH_ASSOC);

// ▼▼▼ 追加: メーカー一覧の取得 (ゲームとガジェットの両方から取得して結合) ▼▼▼
$sql_manufacturers = "
    SELECT DISTINCT manufacturer FROM gg_game
    UNION
    SELECT DISTINCT manufacturer FROM gg_gadget
    ORDER BY manufacturer ASC
";
$manufacturers = $pdo->query($sql_manufacturers)->fetchAll(PDO::FETCH_COLUMN);


// --- パラメータの取得 ---
$sort = $_GET['sort'] ?? 'newest';
$search = $_GET['search'] ?? '';
$type_filter = $_GET['type'] ?? 'all';

// 追加フィルター
$platform_filter = !empty($_GET['platform']) ? $_GET['platform'] : null;
$genre_filter = !empty($_GET['genre']) ? $_GET['genre'] : null;
$category_filter = !empty($_GET['category']) ? $_GET['category'] : null;
$manufacturer_filter = !empty($_GET['manufacturer']) ? $_GET['manufacturer'] : null; // 追加


// --- SQLクエリ構築 ---

// 基本パラメータ
$common_params = [':search' => "%$search%"];
if ($manufacturer_filter) {
    $common_params[':manufacturer'] = $manufacturer_filter;
}

// 1. ゲーム用クエリ部分
$sql_game_where = " WHERE g.game_name LIKE :search";
if ($manufacturer_filter) {
    $sql_game_where .= " AND g.manufacturer = :manufacturer";
}

// 2. ガジェット用クエリ部分
$sql_gadget_where = " WHERE gd.gadget_name LIKE :search";
if ($manufacturer_filter) {
    $sql_gadget_where .= " AND gd.manufacturer = :manufacturer";
}


// --- ゲーム固有の絞り込み ---
$game_specific_where = "";
$game_specific_params = [];
if ($platform_filter) {
    $game_specific_where .= " AND g.platform_id = :platform_id";
    $game_specific_params[':platform_id'] = $platform_filter;
}
if ($genre_filter) {
    $game_specific_where .= " AND EXISTS (SELECT 1 FROM gg_game_genres gg WHERE gg.game_id = g.game_id AND gg.genre_id = :genre_id)";
    $game_specific_params[':genre_id'] = $genre_filter;
}

// --- ガジェット固有の絞り込み ---
$gadget_specific_where = "";
$gadget_specific_params = [];
if ($category_filter) {
    $gadget_specific_where .= " AND gd.category_id = :category_id";
    $gadget_specific_params[':category_id'] = $category_filter;
}


// --- SQLの組み立て ---
$sql_game_full = "
    SELECT 
        'game' as type, g.game_id as id, g.game_name as name, g.manufacturer, g.price, g.stock, g.Sales_Status as status, g.created_time, p.platform_name as category_info
    FROM gg_game g
    JOIN gg_platforms p ON g.platform_id = p.platform_id
    $sql_game_where
";

$sql_gadget_full = "
    SELECT 
        'gadget' as type, gd.gadget_id as id, gd.gadget_name as name, gd.manufacturer, gd.price, gd.stock, gd.Sales_Status as status, gd.created_time, c.category_name as category_info
    FROM gg_gadget gd
    JOIN gg_category c ON gd.category_id = c.category_id
    $sql_gadget_where
";


$final_sql = "";
$final_params = $common_params;

if ($type_filter === 'game') {
    // ゲームのみ
    $final_sql = $sql_game_full . $game_specific_where;
    $final_params = array_merge($final_params, $game_specific_params);

} elseif ($type_filter === 'gadget') {
    // ガジェットのみ
    $final_sql = $sql_gadget_full . $gadget_specific_where;
    $final_params = array_merge($final_params, $gadget_specific_params);

} else {
    // すべて (固有フィルターは適用せず、共通フィルターのみで結合)
    // ※「すべて」選択時にプラットフォーム等の指定が残っていても無視する仕様
    $final_sql = $sql_game_full . " UNION ALL " . $sql_gadget_full;
}


// --- ソート順の適用 ---
switch ($sort) {
    case 'oldest': $order_by = " ORDER BY created_time ASC"; break;
    case 'price_desc': $order_by = " ORDER BY price DESC"; break;
    case 'price_asc': $order_by = " ORDER BY price ASC"; break;
    case 'stock_desc': $order_by = " ORDER BY stock DESC"; break;
    case 'category': $order_by = " ORDER BY type ASC, category_info ASC"; break;
    case 'manufacturer_asc': $order_by = " ORDER BY manufacturer ASC"; break;
    case 'manufacturer_desc': $order_by = " ORDER BY manufacturer DESC"; break;
    default: $order_by = " ORDER BY created_time DESC"; break; // newest
}

$stmt = $pdo->prepare($final_sql . $order_by);
foreach ($final_params as $key => $val) {
    $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="./css/admin_products.css">

<div class="main-content">
    
    <header class="header">
        <button id="sidebar-toggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
        <div class="header-search">
            <form method="GET" action="admin_products.php" class="search-wrapper">
                <input type="text" name="search" value="<?php echo h($search); ?>" placeholder="商品名を検索..." class="search-input">
                <i class="fas fa-search search-icon"></i>
            </form>
        </div>
    </header>

    <main class="page-content">
        <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>
        <div id="loadingOverlay" class="loading-overlay"></div>

        <section id="products-page" class="admin-page">
            
            <div class="page-header-container">
                <h2 class="page-title">商品管理 (全商品)</h2>
                <div style="display:flex; gap:10px;">
                    <a href="admin_add_game.php" class="btn-add">
                        <i class="fas fa-gamepad"></i> ゲーム追加
                    </a>
                    <a href="admin_add_gadget.php" class="btn-add" style="background-color: var(--green);">
                        <i class="fas fa-headphones"></i> ガジェット追加
                    </a>
                </div>
            </div>

            <div class="filter-bar">
                <form method="GET" action="admin_products.php" id="filterForm" class="filter-form">
                    
                    <input type="hidden" name="search" value="<?php echo h($search); ?>">
                    
                    <div class="filter-group">
                        <label for="typeSelect" class="filter-label">種別:</label>
                        <select id="typeSelect" name="type" class="form-select form-select-sm">
                            <option value="all" <?php if($type_filter=='all') echo 'selected'; ?>>すべて</option>
                            <option value="game" <?php if($type_filter=='game') echo 'selected'; ?>>ゲーム</option>
                            <option value="gadget" <?php if($type_filter=='gadget') echo 'selected'; ?>>ガジェット</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="manufacturerSelect" class="filter-label">メーカー:</label>
                        <select id="manufacturerSelect" name="manufacturer" class="form-select form-select-sm">
                            <option value="">全メーカー</option>
                            <?php foreach($manufacturers as $man): ?>
                                <option value="<?php echo h($man); ?>" <?php if($manufacturer_filter == $man) echo 'selected'; ?>>
                                    <?php echo h($man); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="gameFilters" class="extra-filters">
                        <div class="filter-group">
                            <label for="platformSelect" class="filter-label">PF:</label>
                            <select id="platformSelect" name="platform" class="form-select form-select-sm">
                                <option value="">全プラットフォーム</option>
                                <?php foreach($platforms as $pf): ?>
                                    <option value="<?= h($pf['platform_id']) ?>" <?= ($platform_filter == $pf['platform_id']) ? 'selected' : '' ?>>
                                        <?= h($pf['platform_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="genreSelect" class="filter-label">ジャンル:</label>
                            <select id="genreSelect" name="genre" class="form-select form-select-sm">
                                <option value="">全ジャンル</option>
                                <?php foreach($genres as $gn): ?>
                                    <option value="<?= h($gn['genre_id']) ?>" <?= ($genre_filter == $gn['genre_id']) ? 'selected' : '' ?>>
                                        <?= h($gn['genre_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="gadgetFilters" class="extra-filters">
                        <div class="filter-group">
                            <label for="categorySelect" class="filter-label">カテゴリ:</label>
                            <select id="categorySelect" name="category" class="form-select form-select-sm">
                                <option value="">全カテゴリ</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= h($cat['category_id']) ?>" <?= ($category_filter == $cat['category_id']) ? 'selected' : '' ?>>
                                        <?= h($cat['category_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="filter-group" style="margin-left: auto;">
                        <label for="sortSelect" class="filter-label">並び替え:</label>
                        <select id="sortSelect" name="sort" class="form-select form-select-sm">
                            <option value="newest" <?php if($sort=='newest') echo 'selected'; ?>>追加日時 (新しい順)</option>
                            <option value="oldest" <?php if($sort=='oldest') echo 'selected'; ?>>追加日時 (古い順)</option>
                            <option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>価格 (高い順)</option>
                            <option value="price_asc" <?php if($sort=='price_asc') echo 'selected'; ?>>価格 (安い順)</option>
                            <option value="stock_desc" <?php if($sort=='stock_desc') echo 'selected'; ?>>在庫数 (多い順)</option>
                            <option value="manufacturer_asc" <?php if($sort=='manufacturer_asc') echo 'selected'; ?>>メーカー (昇順)</option>
                            <option value="manufacturer_desc" <?php if($sort=='manufacturer_desc') echo 'selected'; ?>>メーカー (降順)</option>
                        </select>
                    </div>

                    <button type="submit" class="button button-secondary" style="padding: 6px 12px; font-size: 0.9rem;">
                        <i class="fas fa-filter"></i> 適用
                    </button>

                </form>
            </div>

            <div class="card">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>商品名 / メーカー</th>
                                <th>カテゴリ / PF</th>
                                <th>価格</th>
                                <th>在庫数</th>
                                <th>販売状況</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                        <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 10px; display:block;"></i>
                                        条件に一致する商品が見つかりません。
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $row): ?>
                                    <tr id="product-row-<?php echo h($row['type']) . '-' . h($row['id']); ?>">
                                        <td>
                                            <div class="product-name-cell">
                                                <span style="font-weight:bold; font-size:0.95rem;">
                                                    <?php echo h($row['name']); ?>
                                                </span>
                                                <span class="product-manufacturer">
                                                    <?php echo h($row['manufacturer']); ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            <?php if ($row['type'] === 'game'): ?>
                                                <span class="category-badge badge-game">GAME</span>
                                                <span style="margin-left: 5px; font-size: 0.85rem;">
                                                    <?php echo h($row['category_info']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="category-badge badge-gadget">GADGET</span>
                                                <span style="margin-left: 5px; font-size: 0.85rem;">
                                                    <?php echo h($row['category_info']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td>¥<?php echo number_format($row['price']); ?></td>

                                        <td>
                                            <?php 
                                            $stock = $row['stock'];
                                            if (is_null($stock)) {
                                                echo '<span style="color: #6b7280; font-size:0.85rem;">- (DL版)</span>';
                                            } else {
                                                $stockClass = ($stock < 5) ? 'stock-low' : 'stock-ok';
                                                echo "<span class='stock-number {$stockClass}'>" . number_format($stock) . "</span>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" 
                                                        <?php echo ($row['status'] == 1) ? 'checked' : ''; ?>
                                                        data-id="<?php echo h($row['id']); ?>"
                                                        data-type="<?php echo h($row['type']); ?>"
                                                        onchange="updateStatus(this)">
                                                <span class="slider"></span>
                                            </label>
                                        </td>

                                        <td class="table-actions">
                                            <?php
                                            $editUrl = ($row['type'] === 'game') 
                                                ? "admin_edit_game.php?id=" . h($row['id']) 
                                                : "admin_edit_gadget.php?id=" . h($row['id']);
                                            ?>
                                            <a href="<?php echo $editUrl; ?>" class="button edit" title="編集" style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; padding:0; border:none; background:none; cursor:pointer; color:inherit;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button class="delete" title="削除" 
                                                    onclick="deleteProduct(<?php echo h($row['id']); ?>, '<?php echo h($row['type']); ?>', '<?php echo h($row['name']); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('typeSelect');
        const gameFilters = document.getElementById('gameFilters');
        const gadgetFilters = document.getElementById('gadgetFilters');
        
        // 各フィルター要素（自動送信のために取得）
        const platformSelect = document.getElementById('platformSelect');
        const genreSelect = document.getElementById('genreSelect');
        const categorySelect = document.getElementById('categorySelect');
        const manufacturerSelect = document.getElementById('manufacturerSelect');
        const sortSelect = document.getElementById('sortSelect');

        function updateFilters() {
            const type = typeSelect.value;

            gameFilters.style.display = 'none';
            gadgetFilters.style.display = 'none';

            platformSelect.disabled = true;
            genreSelect.disabled = true;
            categorySelect.disabled = true;

            if (type === 'game') {
                gameFilters.style.display = 'flex';
                platformSelect.disabled = false;
                genreSelect.disabled = false;
            } else if (type === 'gadget') {
                gadgetFilters.style.display = 'flex';
                categorySelect.disabled = false;
            }
        }

        updateFilters();

        typeSelect.addEventListener('change', function() {
            // 種別変更時は詳細フィルターをリセット
            platformSelect.value = "";
            genreSelect.value = "";
            categorySelect.value = "";
            
            updateFilters();
            document.getElementById('filterForm').submit();
        });

        // フィルター変更時に自動送信
        const autoSubmitInputs = [platformSelect, genreSelect, categorySelect, manufacturerSelect, sortSelect];
        autoSubmitInputs.forEach(input => {
            if(input) {
                input.addEventListener('change', () => document.getElementById('filterForm').submit());
            }
        });

        // サイドバー用
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarClose = document.getElementById('sidebar-close');

        function toggleSidebar() {
            sidebar.classList.toggle('is-open');
            sidebarOverlay.classList.toggle('hidden');
        }

        if(sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
        if(sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
    });

    // ステータス更新
    function updateStatus(checkbox) {
        const id = checkbox.getAttribute('data-id');
        const type = checkbox.getAttribute('data-type');
        const status = checkbox.checked ? 1 : 0;

        fetch('update_product_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, type: type, status: status }),
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('ステータスの更新に失敗しました: ' + (data.message || '不明なエラー'));
                checkbox.checked = !checkbox.checked;
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('通信エラーが発生しました');
            checkbox.checked = !checkbox.checked;
        });
    }

    // 商品削除
    function deleteProduct(id, type, name) {
        if (!confirm(`本当に「${name}」を削除してもよろしいですか？\nこの操作は取り消せません。`)) {
            return;
        }

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'block';

        fetch('delete_product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, type: type }),
        })
        .then(response => response.json())
        .then(data => {
            loadingOverlay.style.display = 'none';
            if (data.success) {
                const rowId = `product-row-${type}-${id}`;
                const row = document.getElementById(rowId);
                if (row) row.remove();
                alert('削除しました。');
            } else {
                alert('削除に失敗しました: ' + (data.message || '不明なエラー'));
            }
        })
        .catch((error) => {
            loadingOverlay.style.display = 'none';
            console.error('Error:', error);
            alert('通信エラーが発生しました');
        });
    }
</script>
</body>
</html>