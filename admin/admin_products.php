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

            <section id="products-page" class="admin-page">
                <div class="page-header">
                    <h2 class="page-title">商品管理 (全商品 14, 15)</h2>
                    <button class="button button-primary">
                        <i class="fas fa-plus"></i> 新規商品を追加 (12, 13)
                    </button>
                </div>

                <div class="card">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>商品名</th>
                                    <th>カテゴリ</th>
                                    <th>在庫</th>
                                    <th>価格</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Elden Ring (Digital)</td>
                                    <td>ゲーム</td>
                                    <td style="color: var(--green);">在庫あり</td>
                                    <td>¥8,980</td>
                                    <td class="table-actions">
                                        <button class="edit"><i class="fas fa-edit"></i></button>
                                        <button class="delete"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4K Gaming Monitor</td>
                                    <td>ガジェット</td>
                                    <td style="color: var(--red);">在庫なし</td>
                                    <td>¥75,000</td>
                                    <td class="table-actions">
                                        <button class="edit"><i class="fas fa-edit"></i></button>
                                        <button class="delete"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="./js/admin.js"></script>
</body>
</html>