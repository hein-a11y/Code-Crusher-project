<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<link rel="stylesheet" href="./css/admin_add_gadget.css">

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