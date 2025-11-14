<?php
// 現在表示しているファイル名を取得 (例: admin_add_game.php)
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store admin</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href=".admin/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<style>

</style>
<body class="body-container">


    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-logo">GG ADMIN</h1>
            <button id="sidebar-close" class="sidebar-close-btn" >
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="admin_dashboard.php" class="sidebar-link <?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                ダッシュボード
            </a>
            <a href="admin_products.php" class="sidebar-link <?php echo ($current_page == 'admin_products.php') ? 'active' : ''; ?>">
                <i class="fas fa-boxes"></i>
                商品一覧
            </a>
            <a href="admin_order_list.php" class="sidebar-link <?php echo ($current_page == 'admin_order_list.php') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i>
                注文一覧
            </a>
            <a href="admin_add_game.php" class="sidebar-link <?php echo ($current_page == 'admin_add_game.php') ? 'active' : ''; ?>">
                <i class="fas fa-gamepad"></i>
                ゲーム追加
            </a>
            <a href="admin_add_gadget.php" class="sidebar-link <?php echo ($current_page == 'admin_add_gadget.php') ? 'active' : ''; ?>">
                <i class="fas fa-headphones"></i>
                ガジェット追加
            </a>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        // サイドバーを開閉する関数
        function toggleSidebar() {
            if (!sidebar || !sidebarOverlay) return;

            if (sidebar.classList.contains('is-open')) {
                // 閉じる
                sidebar.classList.remove('is-open');
                sidebarOverlay.classList.add('hidden');
            } else {
                // 開く
                sidebar.classList.add('is-open');
                sidebarOverlay.classList.remove('hidden');
            }
        }

        // イベントリスナーを設定
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        if (sidebarClose) {
            sidebarClose.addEventListener('click', toggleSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // ウィンドウリサイズ時にモバイルビューで閉じた状態にする
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                if (sidebar && sidebarOverlay) {
                    sidebar.classList.remove('is-open');
                    sidebarOverlay.classList.add('hidden');
                }
            }
        });
    });
    </script>
