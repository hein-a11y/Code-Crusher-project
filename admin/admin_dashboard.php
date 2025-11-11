<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>
<body class="body-container">

    <div class="main-content">
        
        <header class="header">
            <button id="sidebar-toggle" class="sidebar-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-search">
                <div class="search-wrapper">
                    <input type="text" placeholder="注文、商品、またはユーザーを検索..." class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            
            <div class="header-user-controls">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge"></span>
                </button>
                <div class="user-profile">
                    <img class="user-avatar" src="https://placehold.co/40x40/00BFFF/121212?text=AD" alt="Admin Avatar">
                    <span class="user-name">管理者</span>
                </div>
            </div>
        </header>

        <main class="page-content">
            <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

            <section id="dashboard-page" class="admin-page">
                <h2 class="page-title">ダッシュボード概要 (01-08 レイアウトテーマ)</h2>
                
                <div class="stats-grid">
                    <div class="card stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <p>総収益</p>
                                <p>¥1,234,567</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <p class="stat-card-footer positive">+12% (前月比)</p>
                    </div>

                    <div class="card stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <p>新規注文</p>
                                <p>452</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <p class="stat-card-footer negative">-5% (前月比)</p>
                    </div>

                    <div class="card stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <p>アクティブユーザー</p>
                                <p>9,871</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <p class="stat-card-footer positive">+3% (前月比)</p>
                    </div>

                    <div class="card stat-card">
                        <div class="stat-card-content">
                            <div class="stat-card-info">
                                <p>保留中のレビュー</p>
                                <p>21</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="stat-card-footer neutral">要確認</p>
                    </div>
                </div>

                <div class="card recent-orders-card">
                    <h3 class="card-title">最近の注文 (注文一覧 18, 19)</h3>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>注文ID</th>
                                    <th>商品</th>
                                    <th>顧客</th>
                                    <th>ステータス</th>
                                    <th>合計</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#98345</td>
                                    <td>Elden Ring DD</td>
                                    <td>J. Doe</td>
                                    <td><span class="status-badge delivered">配送済み</span></td>
                                    <td>¥8,980</td>
                                </tr>
                                <tr>
                                    <td>#98346</td>
                                    <td>Pro Gaming Headset</td>
                                    <td>A. Smith</td>
                                    <td><span class="status-badge processing">処理中</span></td>
                                    <td>¥15,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="admin_orders.php" class="view-all-link">すべての注文を表示</a>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="./js/admin.js"></script>
</body>
</html>