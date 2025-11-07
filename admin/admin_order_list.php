<?php
// データベース接続関数とエスケープ関数を読み込む
require_once '../functions.php';

try {
    $pdo = getPDO();
    // gg_orders テーブルからデータを取得 (新しい順)
    $sql = $pdo->query('SELECT * FROM gg_orders ORDER BY creation_date DESC');
} catch (PDOException $e) {
    // エラーの場合はメッセージを表示
    $error_message = 'データベースへの接続に失敗しました: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store Admin - 注文リスト</title>
    <link rel="stylesheet" href="./css/admin.css">
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
                <a href="admin_dashboard.php" class="sidebar-link">
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
                <a href="admin_order_list.php" class="sidebar-link active">
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
                    <input type="text" placeholder="注文ID、メールアドレスなどで検索...">
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
                <h2 class="page-title">注文リスト (gg_orders)</h2>

                <?php if (isset($error_message)): ?>
                    <div style="color: red; background-color: #333; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <div class="table-card">
                    <table class="table-auto">
                        <thead>
                            <tr>
                                <th>注文ID</th>
                                <th>ユーザーID</th>
                                <th>注文日時</th>
                                <th>合計金額</th>
                                <th>ステータス</th>
                                <th>配送先メールアドレス</th>
                                <th>配送先住所</th>
                                <!-- <th>使用ポイント</th> -->
                                <!-- <th>獲得ポイント</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($sql)): ?>
                                <?php foreach ($sql as $row): ?>
                                    <?php
                                        // ステータスに応じてクラスを決定
                                        $status = h($row['order_status']);
                                        $status_class = '';
                                        switch (strtolower($status)) {
                                            case 'pending':
                                                $status_class = 'status-pending';
                                                break;
                                            case 'shipped':
                                                $status_class = 'status-shipped';
                                                break;
                                            case 'delivered':
                                                $status_class = 'status-delivered';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'status-cancelled';
                                                break;
                                            default:
                                                $status_class = 'status-pending'; // 不明な場合はPending
                                        }
                                    ?>
                                    <tr>
                                        <td><?php echo h($row['order_id']); ?></td>
                                        <td><?php echo h($row['user_id']); ?></td>
                                        <td class="text-secondary"><?php echo h($row['creation_date']); ?></td>
                                        <td>¥<?php echo number_format(h($row['total'])); ?></td>
                                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status; ?></span></td>
                                        <td class="text-secondary"><?php echo h($row['shipping_mail_address']); ?></td>
                                        <td><?php echo h($row['shipping_address']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

</body>
</html>