<?php
// 本来はセッション確認などの認証処理が入ります
// フォーム送信時の処理 (今回は省略)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // データベースへの保存処理
    // $game_name = $_POST['game_name'];
    // ...
    // $message = "ゲーム「{$game_name}」を追加しました。";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store Admin - ゲーム追加</title>
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
                <a href="admin_add_game.php" class="sidebar-link active">
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
                    <input type="text" placeholder="Search...">
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
                <h2 class="page-title">新規ゲーム追加</h2>

                <?php if (isset($message)): ?>
                    <div class="message-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <form action="admin_add_game.php" method="POST" class="form-card">
                    
                    <h3 class="form-section-title">基本情報 (gg_game)</h3>
                    <div class="form-grid form-grid-cols-2">
                        <div class="form-group">
                            <label for="game_name">ゲーム名</label>
                            <input type="text" id="game_name" name="game_name" required>
                        </div>
                        <div class="form-group">
                            <label for="manufacturer">メーカー</label>
                            <input type="text" id="manufacturer" name="manufacturer" required>
                        </div>
                        <div class="form-group">
                            <label for="platform_id">プラットフォームID</label>
                            <!-- 本来は gg_platforms テーブルから動的に生成 -->
                            <select id="platform_id" name="platform_id" required>
                                <option value="">選択してください</option>
                                <option value="1">PC</option>
                                <option value="2">PlayStation 5</option>
                                <option value="3">PlayStation 4</option>
                                <option value="4">Xbox Series X/S</option>
                                <option value="5">Xbox One</option>
                                <option value="6">Nintendo Switch</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="game_type">ゲームタイプ (Physical/Digital)</label>
                            <select id="game_type" name="game_type" required>
                                <option value="Physical">Physical (パッケージ版)</option>
                                <option value="Digital">Digital (ダウンロード版)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">価格 (円)</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="stock">在庫数</label>
                            <input type="number" id="stock" name="stock" required>
                        </div>
                         <div class="form-group">
                            <label for="Sales_Status">販売ステータス</label>
                            <select id="Sales_Status" name="Sales_Status" required>
                                <option value="1">1 (販売中)</option>
                                <option value="0">0 (販売停止)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="game_explanation">ゲーム説明</label>
                        <textarea id="game_explanation" name="game_explanation" rows="5"></textarea>
                    </div>

                    <!-- 条件: 最低/推奨スペック -->
                    <h3 class="form-section-title">PCスペック (オプション)</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                        ※これらの項目は `gg_game` テーブルに `minimum_specs (TEXT)` と `recommended_specs (TEXT)` カラムが追加されていることを前提としています。
                    </p>
                    <div class="form-grid form-grid-cols-2">
                        <div class="form-group">
                            <label for="minimum_specs">最低スペック</label>
                            <textarea id="minimum_specs" name="minimum_specs" rows="8" placeholder="OS: Windows 10&#10;CPU: Intel Core i5-8400&#10;RAM: 8 GB&#10;GPU: NVIDIA GeForce GTX 1060"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="recommended_specs">推奨スペック</label>
                            <textarea id="recommended_specs" name="recommended_specs" rows="8" placeholder="OS: Windows 11&#10;CPU: Intel Core i7-10700K&#10;RAM: 16 GB&#10;GPU: NVIDIA GeForce RTX 3070"></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-button">ゲームを登録</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

</body>
</html>