<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store admin</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="body-container">

    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-logo">GG ADMIN</h1>
            <button id="sidebar-close" class="sidebar-close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <a href="admin_dashboard.php" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                ダッシュボード
            </a>
            <a href="admin_products.php" class="sidebar-link">
                <i class="fas fa-boxes"></i>
                商品一覧
            </a>
            <a href="admin_order_list.php" class="sidebar-link">
                <i class="fas fa-shopping-cart"></i>
                注文一覧
            </a>
            <a href="admin_add_game.php" class="sidebar-link active">
                <i class="fas fa-gamepad"></i>
                ゲーム追加
            </a>
            <a href="admin_add_gadget.php" class="sidebar-link">
                <i class="fas fa-headphones"></i>
                ガジェット追加
            </a>
            
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link logout">
                    <i class="fas fa-sign-out-alt"></i>
                    サインアウト
                </a>
            </div>
        </nav>
    </div>
