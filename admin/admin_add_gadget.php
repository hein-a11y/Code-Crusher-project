<?php
// 本来はセッション確認などの認証処理が入ります
// フォーム送信時の処理 (今回は省略)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // データベースへの保存処理
    // $gadget_name = $_POST['gadget_name'];
    // ...
    // $message = "ガジェット「{$gadget_name}」を追加しました。";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store Admin - ガジェット追加</title>
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
                <a href="admin_add_gadget.php" class="sidebar-link active">
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
                <h2 class="page-title">新規ガジェット追加</h2>

                <?php if (isset($message)): ?>
                    <div class="message-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <form action="admin_add_gadget.php" method="POST" class="form-card">
                    
                    <h3 class="form-section-title">基本情報 (gg_gadget)</h3>
                    <div class="form-grid form-grid-cols-2">
                        <div class="form-group">
                            <label for="gadget_name">ガジェット名</label>
                            <input type="text" id="gadget_name" name="gadget_name" required>
                        </div>
                        <div class="form-group">
                            <label for="manufacturer">メーカー</label>
                            <input type="text" id="manufacturer" name="manufacturer" required>
                        </div>
                        <div class="form-group">
                            <label for="category_id">カテゴリID</label>
                            <!-- 本来は gg_category テーブルから動的に生成 -->
                            <select id="category_id" name="category_id" required>
                                <option value="">選択してください</option>
                                <option value="1">ヘッドセット</option>
                                <option value="2">ゲーミングマウス</option>
                                <option value="3">ゲーミングキーボード</option>
                                <option value="4">マウスパッド</option>
                                <option value="5">コントローラー</option>
                                <option value="6">ゲーミングチェア</option>
                                <option value="7">マイク</option>
                                <option value="8">ウェブカメラ</option>
                                <option value="9">キャプチャーボード</option>
                                <option value="10">その他</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="connectivity_type">接続タイプ</label>
                            <input type="text" id="connectivity_type" name="connectivity_type" placeholder="例: 無線 (LightSpeed), 有線 (USB-C)">
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
                        <label for="gadget_explanation">ガジェット概要 (短い説明)</label>
                        <textarea id="gadget_explanation" name="gadget_explanation" rows="4"></textarea>
                    </div>

                    <!-- 条件: ガジェットの詳細 -->
                    <h3 class="form-section-title">ガジェット詳細 (オプション)</h3>
                     <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: -0.5rem; margin-bottom: 1rem;">
                        ※この項目は `gg_gadget` テーブルに `gadget_details (TEXT)` カラムが追加されていることを前提としています。
                    </p>
                    <div class="form-group">
                        <label for="gadget_details">詳細スペック・説明</label>
                        <textarea id="gadget_details" name="gadget_details" rows="10" placeholder="[技術仕様]&#10;センサー: HERO 25K&#10;解像度: 100～25,600 DPI&#10;重量: 63g未満&#10;&#10;[特徴]&#10;超軽量設計..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="submit-button">ガジェットを登録</button>
                    </div>
                </form>
            </main>
        </div>
    </div>

</body>
</html>