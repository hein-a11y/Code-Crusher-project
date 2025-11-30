<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<?php
$pdo = getPDO();

// --- 1. KPIデータの取得 ---
// 総収益
$sql = "SELECT SUM(total) FROM gg_orders";
$total_revenue = $pdo->query($sql)->fetchColumn() ?: 0;

// 新規注文数 (今月)
$current_month = date('Y-m');
$sql = "SELECT COUNT(*) FROM gg_orders WHERE DATE_FORMAT(creation_date, '%Y-%m') = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$current_month]);
$new_orders_count = $stmt->fetchColumn();

// 在庫アラート (在庫5以下)
$sql = "SELECT COUNT(*) FROM (
            SELECT stock FROM gg_game WHERE stock <= 5 AND stock IS NOT NULL
            UNION ALL
            SELECT stock FROM gg_gadget WHERE stock <= 5
        ) as low_stock";
$low_stock_count = $pdo->query($sql)->fetchColumn();

// --- 2. リストデータの取得 ---
// 最近の注文
$sql = "SELECT o.order_id, o.total, o.order_status, u.firstname, u.lastname 
        FROM gg_orders o 
        JOIN gg_users u ON o.user_id = u.user_id 
        ORDER BY o.creation_date DESC LIMIT 5";
$recent_orders = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// 在庫アラートリスト
$sql = "SELECT 'game' as type, game_name as name, stock FROM gg_game WHERE stock <= 5 AND stock IS NOT NULL
        UNION ALL
        SELECT 'gadget' as type, gadget_name as name, stock FROM gg_gadget WHERE stock <= 5
        ORDER BY stock ASC LIMIT 5";
$low_stock_items = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// 最近のレビュー
$sql = "SELECT r.rating, r.comment, r.review_date, 
                COALESCE(g.game_name, gd.gadget_name) as product_name,
                u.firstname
        FROM gg_reviews r
        LEFT JOIN gg_game g ON r.game_id = g.game_id
        LEFT JOIN gg_gadget gd ON r.gadget_id = gd.gadget_id
        JOIN gg_users u ON r.user_id = u.user_id
        ORDER BY r.review_date DESC LIMIT 5";
$recent_reviews = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// --- 3. グラフ用データ ---
$sql = "SELECT order_status, COUNT(*) as count FROM gg_orders GROUP BY order_status";
$status_counts = $pdo->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
$json_status_labels = json_encode(array_keys($status_counts));
$json_status_data = json_encode(array_values($status_counts));
?>

<link rel="stylesheet" href="./css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-content">
    
    <header class="header">
        <button id="sidebar-toggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
        <div class="header-search">
            <div class="search-wrapper">
                <input type="text" placeholder="ダッシュボード内を検索..." class="search-input">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
        <div class="header-user-controls" style="display:flex; gap:15px; margin-right:20px;">
            <div class="user-profile">
                <span class="user-name">Admin</span>
                <div style="width:35px; height:35px; border-radius:50%; background:#333; display:flex; align-items:center; justify-content:center; border:1px solid var(--accent-blue);">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </header>

    <main class="page-content">
        <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

        <section id="dashboard-page" class="admin-page">
            <h2 class="page-title">ダッシュボード</h2>
            
            <div class="stats-grid">
                <div class="card stat-card">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-label">総収益</p>
                            <p class="stat-value">¥<?= number_format($total_revenue) ?></p>
                        </div>
                        <div class="stat-icon" style="color: var(--green); border-color: var(--green);">
                            <i class="fas fa-yen-sign"></i>
                        </div>
                    </div>
                    <p class="stat-footer positive"><i class="fas fa-arrow-up"></i> 累計</p>
                </div>

                <div class="card stat-card">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-label">今月の注文</p>
                            <p class="stat-value"><?= number_format($new_orders_count) ?></p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                    <p class="stat-footer neutral">最新月</p>
                </div>

                <div class="card stat-card">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-label">在庫アラート</p>
                            <p class="stat-value" style="color: var(--red);"><?= number_format($low_stock_count) ?></p>
                        </div>
                        <div class="stat-icon" style="color: var(--red); border-color: var(--red);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <p class="stat-footer negative">在庫5以下の商品</p>
                </div>
                
                <div class="card stat-card">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-label">平均注文単価(AOV)</p>
                            <p class="stat-value">¥12,400</p>
                        </div>
                        <div class="stat-icon" style="color: var(--purple); border-color: var(--purple);">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <p class="stat-footer positive"><i class="fas fa-arrow-up"></i> +2.5%</p>
                </div>
            </div>

            <div class="dashboard-grid-2">
                <div class="card">
                    <div class="card-title">
                        <span>売上推移 (直近7日間)</span>
                        <button><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">
                        <span>注文ステータス</span>
                    </div>
                    <div class="chart-wrapper" style="position: relative; height:250px; display:flex; justify-content:center;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid-3">
                <div class="card">
                    <div class="card-title">
                        <span><i class="fas fa-box-open" style="margin-right:8px; color:var(--red);"></i>在庫僅少商品</span>
                        <a href="admin_products.php?sort=stock_asc" style="font-size:0.8rem;">すべて見る</a>
                    </div>
                    <ul class="widget-list">
                        <?php if(empty($low_stock_items)): ?>
                            <li class="widget-item" style="justify-content:center; color:var(--text-secondary);">アラートなし</li>
                        <?php else: ?>
                            <?php foreach($low_stock_items as $item): ?>
                                <li class="widget-item">
                                    <div class="widget-info">
                                        <span class="widget-main-text"><?= h($item['name']) ?></span>
                                        <span class="widget-sub-text"><?= h(ucfirst($item['type'])) ?></span>
                                    </div>
                                    <div class="widget-value text-red">
                                        残 <?= h($item['stock']) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="card">
                    <div class="card-title">
                        <span><i class="fas fa-star" style="margin-right:8px; color:var(--yellow);"></i>新着レビュー</span>
                    </div>
                    <ul class="widget-list">
                        <?php if(empty($recent_reviews)): ?>
                            <li class="widget-item" style="justify-content:center; color:var(--text-secondary);">レビューなし</li>
                        <?php else: ?>
                            <?php foreach($recent_reviews as $review): ?>
                                <li class="widget-item">
                                    <div class="widget-info">
                                        <span class="widget-main-text">
                                            <?= str_repeat('★', $review['rating']) ?><span style="color:#555;"><?= str_repeat('★', 5-$review['rating']) ?></span>
                                        </span>
                                        <span class="widget-sub-text"><?= h($review['product_name']) ?> - <?= h($review['firstname']) ?></span>
                                    </div>
                                    <div class="widget-value" style="font-size:0.75rem; font-weight:normal; color:var(--text-secondary);">
                                        <?= date('m/d', strtotime($review['review_date'])) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="card">
                    <div class="card-title">
                        <span><i class="fas fa-crown" style="margin-right:8px; color:var(--orange);"></i>売れ筋ランキング</span>
                    </div>
                    <ul class="widget-list">
                        <li class="widget-item">
                            <div class="widget-info">
                                <span class="widget-main-text">1. Elden Ring</span>
                                <span class="widget-sub-text">Game</span>
                            </div>
                            <div class="widget-value text-green">128件</div>
                        </li>
                        <li class="widget-item">
                            <div class="widget-info">
                                <span class="widget-main-text">2. G Pro X Superlight</span>
                                <span class="widget-sub-text">Gadget</span>
                            </div>
                            <div class="widget-value text-green">95件</div>
                        </li>
                        <li class="widget-item">
                            <div class="widget-info">
                                <span class="widget-main-text">3. Apex Pro TKL</span>
                                <span class="widget-sub-text">Gadget</span>
                            </div>
                            <div class="widget-value text-green">82件</div>
                        </li>
                        <li class="widget-item">
                            <div class="widget-info">
                                <span class="widget-main-text">4. Cyberpunk 2077</span>
                                <span class="widget-sub-text">Game</span>
                            </div>
                            <div class="widget-value text-green">76件</div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    <span>最近の注文</span>
                    <a href="admin_order_list.php" style="font-size:0.85rem;">注文一覧へ</a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>注文ID</th>
                                <th>顧客名</th>
                                <th>ステータス</th>
                                <th>合計金額</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?= h($order['order_id']) ?></td>
                                    <td><?= h($order['firstname'] . ' ' . $order['lastname']) ?></td>
                                    <td>
                                        <?php 
                                            $status = $order['order_status'];
                                            $badgeClass = 'status-pending';
                                            if($status === 'Delivered') $badgeClass = 'status-delivered';
                                            elseif($status === 'Shipped') $badgeClass = 'status-shipped';
                                            elseif($status === 'Processing') $badgeClass = 'status-processing';
                                        ?>
                                        <span class="status-badge <?= $badgeClass ?>"><?= h($status) ?></span>
                                    </td>
                                    <td>¥<?= number_format($order['total']) ?></td>
                                    <td><button><i class="fas fa-eye"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. 売上推移チャート (ダミーデータ) ---
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['11/24', '11/25', '11/26', '11/27', '11/28', '11/29', '11/30'],
                datasets: [{
                    label: '売上 (円)',
                    data: [120000, 150000, 180000, 130000, 200000, 250000, 220000],
                    borderColor: '#00BFFF',
                    backgroundColor: 'rgba(0, 191, 255, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { grid: { color: '#333' }, ticks: { color: '#aaa' } },
                    x: { grid: { display: false }, ticks: { color: '#aaa' } }
                }
            }
        });

        // --- 2. 注文ステータスチャート (PHPデータ使用) ---
        const statusLabels = <?= $json_status_labels ?>;
        const statusData = <?= $json_status_data ?>;
        
        const labels = statusLabels.length ? statusLabels : ['Pending', 'Shipped', 'Delivered'];
        const data = statusData.length ? statusData : [5, 8, 12];

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#facc15', '#60a5fa', '#4ade80', '#f87171'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { color: '#fff' } }
                },
                cutout: '70%'
            }
        });

        // サイドバー制御
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            if (!sidebar) return;
            sidebar.classList.toggle('is-open');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('hidden');
        }

        if(sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
        if(sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    });
</script>
</body>
</html>