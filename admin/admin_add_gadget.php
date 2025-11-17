<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

    <style>
        /* Chrome, Safari, Edge, Opera で数値入力の矢印を消す */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox で数値入力の矢印を消す */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* スペック入力行のレイアウト */
        .spec-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        
        /* 削除ボタンのスタイル調整 */
        .remove-spec-btn {
            color: var(--red);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background-color: rgba(248, 113, 113, 0.1);
            transition: background-color 0.3s;
        }
        .remove-spec-btn:hover {
            background-color: rgba(248, 113, 113, 0.2);
        }
    </style>

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
                            <label class="form-label">スペック情報</label>
                            
                            <div id="specs-container">
                                <div class="spec-row">
                                    <input type="text" name="spec_name[]" class="form-input" placeholder="項目名 (例: 重さ)" style="flex: 2;">
                                    <input type="text" name="spec_value[]" class="form-input" placeholder="値 (例: 63)" style="flex: 2;">
                                    <input type="text" name="spec_unit[]" class="form-input" placeholder="単位 (例: g)" style="flex: 1;">
                                    <div style="width: 30px;"></div> 
                                </div>
                            </div>

                            <button type="button" id="add-spec-btn" class="button button-secondary" style="width: 100%; margin-top: 10px; border-style: dashed;">
                                <i class="fas fa-plus"></i> スペックを追加
                            </button>
                        </div>
                        </div> 
                    
                    <div class="card">
                        <button type="submit" class="button button-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> ガジェットを登録する
                        </button>
                    </div>
                </form>

            </section>
        </main>
    </div>

    <script src="./js/admin_add_gadget.js"></script>
</body>
</html>