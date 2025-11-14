<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

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
            
        </header>

        <main class="page-content">
            <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

            <section id="add-game-page" class="admin-page">
                <h2 class="page-title">新規ゲーム追加</h2>
                
                <form action="#" method="POST">
                    <div class="card">
                        <h3 class="card-title">ゲーム情報</h3>
                        
                        <div class="form-group">
                            <label for="game_name" class="form-label">ゲームタイトル</label>
                            <input type="text" id="game_name" name="game_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="game_explanation" class="form-label">ゲーム説明</label>
                            <textarea id="game_explanation" name="game_explanation" class="form-textarea"></textarea>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="platform_id" class="form-label">プラットフォーム</label>
                                <select id="platform_id" name="platform_id" class="form-select" required>
                                    <option value="">選択してください...</option>
                                    <option value="1">PC</option>
                                    <option value="2">PlayStation 5</option>
                                    <option value="6">Nintendo Switch</option>
                                    <option value="4">Xbox Series X/S</option>
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
                                <input type="number" id="price" name="price" class="form-input" required placeholder="例: 8980">
                            </div>
                            <div class="form-group">
                                <label for="stock" class="form-label">在庫数 (物理版の場合)</label>
                                <input type="number" id="stock" name="stock" class="form-input" placeholder="デジタル版は空欄">
                            </div>
                        </div>
                    </div> <div class="card">
                        <button type="submit" class="button button-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> ゲームを登録する
                        </button>
                    </div>
                </form>

            </section>
        </main>
    </div>
</body>
</html>