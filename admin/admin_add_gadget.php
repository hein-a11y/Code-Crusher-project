<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<style>
        /* --- 基本設定 --- */
        /* 数値入力の矢印（スピナー）を非表示 */
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
            align-items: flex-start;
        }
        .spec-input-wrapper {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        /* 「その他」選択時の入力欄（初期非表示） */
        .spec-custom-input {
            display: none;
        }
        
        /* 幅調整用クラス */
        .flex-grow-2 { flex: 2; }
        .flex-grow-1 { flex: 1; }
        .spacer-icon { width: 30px; }

        /* --- ボタン類のスタイル --- */
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
            margin-top: 8px;
        }
        .remove-spec-btn:hover {
            background-color: rgba(248, 113, 113, 0.2);
        }
        .button-dashed {
            width: 100%;
            margin-top: 10px;
            border-style: dashed;
            border-width: 1px;
            border-color: var(--border-color);
        }
        .button-full { width: 100%; }

        /* --- カテゴリ「その他」入力欄 --- */
        #new_category_wrapper {
            display: none;
            margin-top: 10px;
        }

        /* --- 画像アップロードエリアのスタイル --- */
        .form-help-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 10px;
        }

        .image-slot {
            aspect-ratio: 1 / 1;
            background-color: #ebf0f3; /* 画像に近い薄い色 */
            border: 1px solid #ccc;
            border-radius: 4px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
            overflow: visible;
        }
        .image-slot:hover {
            background-color: #dbe4e9;
        }
        .image-slot.dragover {
            background-color: rgba(0, 191, 255, 0.2);
            border-color: var(--accent-blue);
        }

        .image-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 3px;
        }

        /* プレースホルダー（カメラアイコン等） */
        .slot-placeholder {
            text-align: center;
            color: #555;
            font-size: 13px;
            font-weight: bold;
            pointer-events: none;
        }
        .slot-placeholder i {
            font-size: 28px;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }

        /* 削除ボタン */
        .slot-delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            z-index: 10;
            transition: background-color 0.2s;
        }
        .slot-delete-btn:hover {
            background-color: #ff4444;
        }

        /* メイン画像ラベル */
        .main-image-label {
            position: absolute;
            bottom: -25px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: var(--text-primary);
            font-weight: bold;
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
                
                <form action="./gadget-insert.php" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">商品画像 (最大9枚)</label>
                            <p class="form-help-text">
                                複数のファイルをアップロード、または以下で1点以上のファイルをドラッグアンドドロップします。
                            </p>
                            
                            <input type="file" id="gadget_images" name="gadget_images[]" multiple accept="image/*" style="display:none;">
                            
                            <div id="image-grid-container" class="image-grid">
                                </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">スペック情報</label>
                            
                            <div id="specs-container">
                                <div class="spec-row">
                                    <div class="spec-input-wrapper">
                                        <select name="spec_name[]" class="form-select spec-select" style="width: 100%;">
                                            <option value="" data-unit="">項目を選択...</option>
                                            <?php
                                            $pdo = getPDO();
                                            $sql = $pdo->query('SELECT * FROM gg_specifications ORDER BY spec_id ASC');

                                            foreach ($sql as $row) {
                                                $spec_id = h($row['spec_id']);
                                                $spec_name = h($row['spec_name']);
                                                $unit = h($row['unit']);
                                                echo <<< HTML
                                                <option value="{$spec_id}" data-unit="{$unit}">$spec_name</option>
                                                HTML;
                                            }
                                            ?>
                                            <option value="other" data-unit="">その他</option>
                                        </select>
                                        <input type="text" name="spec_custom_name[]" class="form-input spec-custom-input" placeholder="項目名を入力">
                                    </div>

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