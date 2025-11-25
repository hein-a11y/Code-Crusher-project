<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

    <style>
        /* --- 数値入力の矢印（スピナー）を非表示にする設定 --- */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* --- スペック入力エリアのレイアウト --- */
        .spec-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: flex-start; /* 上揃えに変更（その他入力欄が出た時のため） */
        }

        /* フレックスボックスの比率調整用クラス */
        .flex-grow-2 {
            flex: 2;
        }
        .flex-grow-1 {
            flex: 1;
        }
        .spacer-icon {
            width: 30px; /* 削除ボタンと同じ幅のスペーサー */
        }

        /* --- ボタン類の追加スタイル --- */
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
            cursor: pointer;
            border: none;
            margin-top: 8px; /* セレクトボックスの高さに合わせる微調整 */
        }
        .remove-spec-btn:hover {
            background-color: rgba(248, 113, 113, 0.2);
        }

        /* 点線ボーダーの追加ボタン */
        .button-dashed {
            width: 100%;
            margin-top: 10px;
            border-style: dashed;
            border-width: 1px;
            border-color: var(--border-color); /* admin.cssの変数を参照 */
        }

        /* 全幅ボタン */
        .button-full {
            width: 100%;
        }

        /* --- 新規カテゴリ入力エリア --- */
        #new_category_wrapper {
            display: none;    /* 初期状態は非表示 */
            margin-top: 10px; /* 上部に余白 */
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
                
                <form action="./gadget-insert.php" method="POST">
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
                                    <?php
                                    $pdo = getPDO();
                                    $sql = $pdo->query('SELECT * FROM gg_category ORDER BY category_id ASC');
                                    foreach ($sql as $row) {
                                        $id = h($row['category_id']);
                                        $name = h($row['category_name']);
                                        echo <<< HTML
                                        <option value="{$id}">$name</option>
                                        HTML;
                                        echo '';
                                    }
                                    ?>
                                    <option value="new">その他</option>
                                </select>
                                
                                <div id="new_category_wrapper">
                                    <input type="text" id="new_category" name="new_category" class="form-input" placeholder="新規カテゴリ名を入力してください">
                                </div>
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
                                        <select name="spec_name[]" class="form-select flex-grow-2 spec-select">
                                            <option value="" data-unit="">項目を選択...</option>
                                            <?php
                                            $pdo = getPDO();
                                            $sql = $pdo->query('SELECT * FROM gg_specifications ORDER BY spec_id ASC');

                                            foreach ($sql as $row) {
                                                $spec_id = h($row['spec_id']);
                                                $spec_name = h($row['spec_name']);
                                                $unit = h($row['unit']);
                                                // ▼ data-unit 属性を追加
                                                echo <<< HTML
                                                <option value="{$spec_id}" data-unit="{$unit}">$spec_name</option>
                                                HTML;
                                            }
                                            ?>
                                        </select>

                                        <input type="text" name="spec_value[]" class="form-input flex-grow-2" placeholder="値 (例: 63)">
                                        <input type="text" name="spec_unit[]" class="form-input flex-grow-1 spec-unit" placeholder="単位 (例: g)">
                                        <div class="spacer-icon"></div> 
                                    </div>
                                </div>

                                <button type="button" id="add-spec-btn" class="button button-secondary button-dashed">
                                    <i class="fas fa-plus"></i> スペックを追加
                                </button>
                            </div>
                        </div> 
                    
                    <div class="card">
                        <button type="submit" class="button button-primary button-full">
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