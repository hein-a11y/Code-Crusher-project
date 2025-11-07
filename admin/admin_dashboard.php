<?php
// 本来はセッション確認などの認証処理が入ります
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store Admin - Dashboard</title>
    <!-- 共通CSS -->
    <link rel="stylesheet" href="./css/admin.css">
    <!-- Font Awesome (アイコン) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="admin-container">

        <!-- サイドバー -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">GG ADMIN</h1>
            </div>
            
            <nav class="sidebar-nav">
                <a href="admin_dashboard.php" class="sidebar-link active">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a>
                <a href="admin_add_game.php" class="sidebar-link">
                    <i class="fas fa-gamepad"></i>
                    ゲーム追加
                </a>
                <a href="admin_add_gadget.php" class="sidebar-link">
                    <i class="fas fa-mouse"></i>
                    ガジェット追加
                </a>
                <a href="admin_order_list.php" class="sidebar-link">
                    <i class="fas fa-shopping-cart"></i>
                    注文リスト
                </a>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-users"></i>
                    全ユーザー
                </a>
                
                <div class="sidebar-divider">
                    <a href="#" class="sidebar-link logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Sign Out
                    </a>
                </div>
            </nav>
        </aside>

        <!-- メインコンテンツ -->
        <div class="main-content">
            
            <!-- トップヘッダー -->
            <header class="main-header">
                <div class="header-search">
                    <input type="text" placeholder="Search orders, products, or users...">
                    <!-- <i class="fas fa-search"></i> (実装するならposition:absoluteで) -->
                </div>
                
                <div class="header-user">
                    <div class="header-user-avatar">
                        <img src="https://placehold.co/40x40/00BFFF/121212?text=AD" alt="Admin Avatar">
                    </div>
                    <span>Admin User</span>
                </div>
            </header>

            <!-- ページコンテンツ -->
            <main class="page-content">
                <h2 class="page-title">Dashboard Summary</h2>
                
                <p>ここにダッシュボードの統計情報（売上、注文件数など）を表示します。</p>
                <!-- 
                    admin.php からのコピー (Tailwindクラスは削除)
                    統計カードなどをここに配置できます
                -->

            </main>
        </div>
    </div>

</body>
</html>