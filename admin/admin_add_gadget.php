<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG Store 管理 - ガジェット追加</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
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

            <section id="add-gadget-page" class="admin-page">
                <h2 class="page-title">新規ガジェット追加</h2>
                
                <form action="#" method="POST">
                    <div class="card">
                        <h3 class="card-title">ガジェット情報</h3>
                        
                        <div class="form-group">
                            <label for="gadget_name" class="form-label">ガジェット名</label>
                            <input type="text" id="gadget_name" name="gadget_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="gadget_explanation" class="form-label">ガジェット説明</label>
                            <textarea id="gadget_explanation" name="gadget_explanation" class="form-textarea"></textarea>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="category_id" class="form-label">カテゴリ</label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    <option value="">選択してください...</option>
                                    <option value="1">ヘッドセット</option>
                                    <option value="2">ゲーミングマウス</option>
                                    <option value="3">キーボード</option>
                                    <option value="5">コントローラー</option>
                                    <option value="7">マイク</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="manufacturer" class="form-label">メーカー</label>
                                <input type="text" id="manufacturer" name="manufacturer" class="form-input">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="price" class="form-label">価格 (円)</label>
                                <input type="number" id="price" name="price" class="form-input" required placeholder="例: 15800">
                            </div>
                            <div class="form-group">
                                <label for="stock" class="form-label">在庫数</label>
                                <input type="number" id="stock" name="stock" class="form-input" required placeholder="例: 50">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="connectivity_type" class="form-label">接続タイプ (コンマ区切り)</label>
                            <input type="text" id="connectivity_type" name="connectivity_type" class="form-input" placeholder="例: 無線 (LightSpeed),有線 (USB-C)">
                        </div>
                    </div> <div class="card">
                        <button type="submit" class="button button-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> ガジェットを登録する
                        </button>
                    </div>
                </form>

            </section>
        </main>
    </div>

    <script src="./js/admin.js"></script>
</body>
</html>