<?php require_once '../header.php'; ?>
<?php require '../functions.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADGET STORE - 商品一覧</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Sans+JP:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php $gadgets_css = __DIR__ . '/css/gadgets.css'; ?>
    <link rel="stylesheet" href="./css/gadgets.css?v=<?php echo is_file($gadgets_css) ? filemtime($gadgets_css) : time(); ?>">
    <script src="./js/price-filter.js" defer></script>
</head>
<body>

    <div class="container">
        
        <aside class="sidebar">
            <div class="filter-group">
                <h3 class="filter-title">カテゴリー</h3>
                <ul class="filter-list">
                    <li><a href="GADGETS.php" class="<?php echo empty($_GET['category_id']) ? 'active' : ''; ?>">すべて</a></li>
                    <?php
                    $pdo = getPDO();
                    $selected_category = $_GET['category_id'] ?? null;
                    // 販売中のガジェットに実際に存在するカテゴリーのみ取得
                    $categories = $pdo->query('SELECT DISTINCT c.category_id, c.category_name FROM gg_category c INNER JOIN gg_gadget g ON c.category_id = g.category_id WHERE g.Sales_Status = 1 ORDER BY c.category_name ASC')->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($categories as $category) {
                        $is_active = ($selected_category == $category['category_id']) ? 'active' : '';
                        echo '<li><a href="GADGETS.php?category_id=' . h($category['category_id']) . '" class="' . $is_active . '">' . h($category['category_name']) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="filter-group">
                <h3 class="filter-title">メーカー</h3>
                <form method="GET" id="filter-form">
                    <?php
                    if (!empty($_GET['category_id'])) {
                        echo '<input type="hidden" name="category_id" value="' . h($_GET['category_id']) . '">';
                    }
                    
                    // カテゴリーが選択されているか判定
                    $category_id = $_GET['category_id'] ?? null;
                    
                    if (!empty($category_id)) {
                        // カテゴリーが選択されている場合、そのカテゴリーに属するガジェットのメーカーのみ取得
                        $sql = 'SELECT DISTINCT manufacturer FROM gg_gadget WHERE Sales_Status = 1 AND category_id = ? AND manufacturer IS NOT NULL AND manufacturer != "" ORDER BY manufacturer ASC';
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$category_id]);
                        $manufacturers = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    } else {
                        // カテゴリーが選択されていない場合、すべての販売中ガジェットのメーカーを取得
                        $manufacturers = $pdo->query('SELECT DISTINCT manufacturer FROM gg_gadget WHERE Sales_Status = 1 AND manufacturer IS NOT NULL AND manufacturer != "" ORDER BY manufacturer ASC')->fetchAll(PDO::FETCH_COLUMN);
                    }
                    
                    $selected_manufacturers = $_GET['manufacturer'] ?? [];
                    if (!is_array($selected_manufacturers)) {
                        $selected_manufacturers = [$selected_manufacturers];
                    }
                    foreach ($manufacturers as $manufacturer) {
                        $is_checked = in_array($manufacturer, $selected_manufacturers) ? 'checked' : '';
                        echo '<label class="checkbox-label"><input type="checkbox" name="manufacturer[]" value="' . h($manufacturer) . '" ' . $is_checked . ' onchange="document.getElementById(\'filter-form\').submit();"> ' . h($manufacturer) . '</label>';
                    }
                    ?>
                </form>
            </div>

            <div class="filter-group">
                <h3 class="filter-title">価格帯</h3>
                <?php
                // 登録されているガジェットから最低価格と最高価格を取得
                $price_sql = 'SELECT MIN(price) as min_price, MAX(price) as max_price FROM gg_gadget WHERE Sales_Status = 1';
                $price_result = $pdo->query($price_sql)->fetch(PDO::FETCH_ASSOC);
                $min_price = intval($price_result['min_price']) ?? 0;
                $max_price = intval($price_result['max_price']) ?? 100000;
                
                // URL パラメータから現在の価格範囲を取得
                $current_min = isset($_GET['price_min']) ? intval($_GET['price_min']) : $min_price;
                $current_max = isset($_GET['price_max']) ? intval($_GET['price_max']) : $max_price;
                ?>
                <form method="GET" id="price-filter-form">
                    <?php
                    // 既存のパラメータを保持
                    if (!empty($_GET['category_id'])) {
                        echo '<input type="hidden" name="category_id" value="' . h($_GET['category_id']) . '">';
                    }
                    if (!empty($_GET['manufacturer'])) {
                        $manufacturers_list = $_GET['manufacturer'];
                        if (is_array($manufacturers_list)) {
                            foreach ($manufacturers_list as $mfg) {
                                echo '<input type="hidden" name="manufacturer[]" value="' . h($mfg) . '">';
                            }
                        }
                    }
                    if (!empty($_GET['page'])) {
                        echo '<input type="hidden" name="page" value="' . h($_GET['page']) . '">';
                    }
                    ?>
                    <div class="price-range-container">
                        <div class="price-sliders">
                            <div class="price-range-highlight" aria-hidden="true"></div>
                            <input type="range" id="price-min-slider" class="price-slider" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $current_min; ?>" step="1000">
                            <input type="range" id="price-max-slider" class="price-slider" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $current_max; ?>" step="1000">
                        </div>
                        <div class="price-display">
                            <input type="number" id="price-min-input" name="price_min" class="price-number-input" value="<?php echo $current_min; ?>" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>">
                            <span class="price-separator">〜</span>
                            <input type="number" id="price-max-input" name="price_max" class="price-number-input" value="<?php echo $current_max; ?>" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn-filter" style="width: 100%; margin-top: 10px;">検索</button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Gadgets</h1>
                <select class="sort-select">
                    <option>新着順</option>
                    <option>価格が安い順</option>
                    <option>価格が高い順</option>
                    <option>人気順</option>
                </select>
            </div>

            <div class="product-grid">
                <?php
                $pdo = getPDO();

                // フィルター条件を構築
                $where_conditions = ['Sales_Status = 1'];
                $params = [];

                // キーワード検索
                if (isset($_GET['keyword'])) {
                    $where_conditions[] = 'gadget_name LIKE ?';
                    $params[] = '%' . $_GET['keyword'] . '%';
                }

                // カテゴリーフィルター
                if (!empty($_GET['category_id'])) {
                    $where_conditions[] = 'category_id = ?';
                    $params[] = $_GET['category_id'];
                }

                // メーカーフィルター
                if (!empty($_GET['manufacturer'])) {
                    $manufacturers = $_GET['manufacturer'];
                    if (!is_array($manufacturers)) {
                        $manufacturers = [$manufacturers];
                    }
                    $placeholders = implode(',', array_fill(0, count($manufacturers), '?'));
                    $where_conditions[] = "manufacturer IN ($placeholders)";
                    $params = array_merge($params, $manufacturers);
                }

                // 価格範囲フィルター
                if (!empty($_GET['price_min']) || !empty($_GET['price_max'])) {
                    $price_min = !empty($_GET['price_min']) ? intval($_GET['price_min']) : 0;
                    $price_max = !empty($_GET['price_max']) ? intval($_GET['price_max']) : 9999999;
                    $where_conditions[] = 'price >= ? AND price <= ?';
                    $params[] = $price_min;
                    $params[] = $price_max;
                }

                // ページネーション設定
                $items_per_page = 50;
                $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

                // 全商品数を取得
                $count_sql = 'SELECT COUNT(*) FROM gg_gadget WHERE ' . implode(' AND ', $where_conditions);
                $count_stmt = $pdo->prepare($count_sql);
                $count_stmt->execute($params);
                $total_items = $count_stmt->fetchColumn();
                $total_pages = ceil($total_items / $items_per_page);

                // オフセットを計算
                $offset = ($current_page - 1) * $items_per_page;

                $sql_str = 'SELECT * FROM gg_gadget WHERE ' . implode(' AND ', $where_conditions) . ' LIMIT ' . intval($items_per_page) . ' OFFSET ' . intval($offset);
                
                $sql = $pdo->prepare($sql_str);
                $sql->execute($params);

                if ($total_items == 0) {
                    echo '<p style="text-align: center; color: var(--text-sub); padding: 40px;">該当する商品がありません</p>';
                } else {
                    foreach ($sql as $row) {
                        $id = h($row['gadget_id']);
                        $name = h($row['gadget_name']);
                        $manufacturer = h($row['manufacturer']);
                        $price_number_format = number_format(h($row['price']));
                        $sale_status = h($row['Sales_Status']);
                        $keyword = isset($_GET['keyword']) ? h($_GET['keyword']) : '';

                        // 画像取得
                        $media_stmt = $pdo->prepare('SELECT url, is_primary FROM gg_media WHERE gadget_id = ?');
                        $media_stmt->execute([$id]);
                        
                        $main_img = "https://placehold.co/300x300/ffffff/333333?text=No+Image"; // デフォルト画像
                        foreach ($media_stmt as $media) {
                            if ($media['is_primary'] == 1) {
                                $main_img = h($media['url']);
                                break;
                            }
                        }

                        // 販売中の場合のみ表示
                        if ($sale_status == 1) {
                            // 詳細ページへのリンク
                            $detail_link = "./gadget-details.php?name={$name}&keyword={$keyword}&id={$id}";
                            
                            echo <<<HTML
                            <article class="product-card">
                                <a href="{$detail_link}" class="card-link">
                                    <div class="card-image">
                                        <img src="{$main_img}" alt="{$name}">
                                    </div>
                                    <div class="card-body">
                                        <span class="card-brand">{$manufacturer}</span>
                                        <h2 class="card-title">{$name}</h2>
                                        
                                        <div class="card-rating">
                                            ★★★★☆<span class="rating-count">(24)</span>
                                        </div>
                                        
                                        <p class="card-price">¥{$price_number_format}</p>
                                        <div class="card-action">詳細を見る</div>
                                    </div>
                                </a>
                            </article>
                            HTML;
                        }
                    }
                }
                ?>
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <?php
                // 前ページボタン
                if ($current_page > 1) {
                    $prev_page = $current_page - 1;
                    $query_string = http_build_query(array_merge($_GET, ['page' => $prev_page]));
                    echo '<a href="GADGETS.php?' . $query_string . '" style="background:transparent; border:1px solid #333; color:#fff; padding:8px 15px; border-radius:4px; cursor:pointer; text-decoration: none; display: inline-block; margin-right: 10px;">&lt;</a>';
                }

                // ページ番号表示
                $range = 3; // 前後3ページを表示
                $start_page = max(1, $current_page - $range);
                $end_page = min($total_pages, $current_page + $range);

                if ($start_page > 1) {
                    $query_string = http_build_query(array_merge($_GET, ['page' => 1]));
                    echo '<a href="GADGETS.php?' . $query_string . '" style="margin:0 5px; color:var(--text-sub); text-decoration: none; cursor: pointer;">1</a>';
                    if ($start_page > 2) {
                        echo '<span style="margin:0 5px; color:var(--text-sub);">...</span>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $current_page) {
                        echo '<span style="margin:0 10px; font-weight:bold; color:var(--primary-color);">' . $i . '</span>';
                    } else {
                        $query_string = http_build_query(array_merge($_GET, ['page' => $i]));
                        echo '<a href="GADGETS.php?' . $query_string . '" style="margin:0 10px; color:var(--text-sub); text-decoration: none; cursor: pointer;">' . $i . '</a>';
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<span style="margin:0 5px; color:var(--text-sub);">...</span>';
                    }
                    $query_string = http_build_query(array_merge($_GET, ['page' => $total_pages]));
                    echo '<a href="GADGETS.php?' . $query_string . '" style="margin:0 5px; color:var(--text-sub); text-decoration: none; cursor: pointer;">' . $total_pages . '</a>';
                }

                // 次ページボタン
                if ($current_page < $total_pages) {
                    $next_page = $current_page + 1;
                    $query_string = http_build_query(array_merge($_GET, ['page' => $next_page]));
                    echo '<a href="GADGETS.php?' . $query_string . '" style="background:transparent; border:1px solid #333; color:#fff; padding:8px 15px; border-radius:4px; cursor:pointer; text-decoration: none; display: inline-block; margin-left: 10px;">&gt;</a>';
                }
                ?>
            </div>

        </main>
    </div>

</body>
</html>