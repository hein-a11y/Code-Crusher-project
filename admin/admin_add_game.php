<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<?php
// スペック値の候補をDBから取得
$pdo = getPDO();
$spec_values_map = [];
try {
    $sql = $pdo->query("SELECT spec_id, spec_value FROM gg_game_requirements GROUP BY spec_id, spec_value ORDER BY spec_value ASC");
    foreach ($sql as $row) {
        $spec_values_map[$row['spec_id']][] = h($row['spec_value']);
    }
} catch (Exception $e) {
    // エラー時は空で続行
}
$json_spec_values = json_encode($spec_values_map);
?>

<link rel="stylesheet" href="./css/admin_add_game.css">

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
            
            <form action="./game-insert.php" method="POST" enctype="multipart/form-data">
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
                                <?php
                                $sql = $pdo->query('SELECT * FROM gg_platforms ORDER BY platform_id ASC');
                                foreach ($sql as $row) {
                                    $id = h($row['platform_id']);
                                    $name = h($row['platform_name']);
                                    echo <<<HTML
                                    <option value="{$id}">{$name}</option>
                                    HTML;
                                }
                                ?>
                                <option value="new">その他</option>
                            </select>
                            <div id="new_platform_wrapper" class="custom-input-wrapper">
                                <input type="text" id="new_platform" name="new_platform" class="form-input" placeholder="新規プラットフォーム名">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ジャンル (複数選択可)</label>
                            <div class="checkbox-list">
                                <?php
                                $sql = $pdo->query('SELECT * FROM gg_genres ORDER BY genre_id ASC');
                                foreach ($sql as $row) {
                                    $id = h($row['genre_id']);
                                    $name = h($row['genre_name']);
                                    echo <<<HTML
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="genre_ids[]" value="{$id}">
                                        {$name}
                                    </label>
                                    HTML;
                                }
                                ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="genre_ids[]" value="new" id="genre_other">
                                    その他
                                </label>
                            </div>
                            
                            <div id="new_genre_wrapper" class="custom-input-wrapper">
                                <input type="text" id="new_genre" name="new_genre" class="form-input" placeholder="新規ジャンル名">
                            </div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="manufacturer" class="form-label">メーカー</label>
                            <input type="text" id="manufacturer" name="manufacturer" class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="game_type" class="form-label">販売形式</label>
                            <select id="game_type" name="game_type" class="form-select">
                                <option value="Physical">パッケージ版 (Physical)</option>
                                <option value="Digital">デジタル版 (Digital)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="price" class="form-label">価格 (円)</label>
                            <input type="number" id="price" name="price" class="form-input" required placeholder="例: 8980">
                        </div>
                        <div class="form-group">
                            <label for="stock" class="form-label">在庫数 (物理版のみ)</label>
                            <input type="number" id="stock" name="stock" class="form-input" placeholder="デジタル版は空欄で可">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ゲーム画像 (最大9枚)</label>
                        <p class="form-help-text">
                            複数のファイルをアップロード、または以下で1点以上のファイルをドラッグアンドドロップします。
                        </p>
                        
                        <input type="file" id="game_images" name="game_images[]" multiple accept="image/*" style="display:none;">
                        
                        <div id="image-grid-container" class="image-grid">
                            </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="card-title">システム要件 (任意)</h3>

                    <template id="spec-name-options">
                        <option value="" data-unit="">項目を選択...</option>
                        <?php
                        $sql = $pdo->query("SELECT * FROM gg_specifications WHERE product_type IN ('GAME', 'BOTH') ORDER BY spec_id ASC");
                        foreach ($sql as $row) {
                            $spec_id = h($row['spec_id']);
                            $spec_name = h($row['spec_name']);
                            $unit = h($row['unit']);
                            echo <<<HTML
                            <option value="{$spec_id}" data-unit="{$unit}">{$spec_name}</option>
                            HTML;
                        }
                        ?>
                        <option value="other" data-unit="">その他</option>
                    </template>

                    <div class="spec-section">
                        <h4>最低動作環境 (Minimum Requirements)</h4>
                        <div id="min-specs-container"></div>
                        <button type="button" id="add-min-spec-btn" class="button button-secondary button-dashed">
                            <i class="fas fa-plus"></i> 項目を追加
                        </button>
                    </div>

                    <div class="spec-section">
                        <h4>推奨動作環境 (Recommended Requirements)</h4>
                        <div id="rec-specs-container"></div>
                        <button type="button" id="add-rec-spec-btn" class="button button-secondary button-dashed">
                            <i class="fas fa-plus"></i> 項目を追加
                        </button>
                    </div>
                </div>

                <div class="card">
                    <button type="submit" class="button button-primary button-full">
                        <i class="fas fa-save"></i> ゲームを登録する
                    </button>
                </div>
            </form>

        </section>
    </main>
</div>

<script>
// PHPから渡された過去のスペック値データをグローバルで設定
window.specValuesMap = <?php echo $json_spec_values; ?>;
</script>
<script src="./js/admin_add_game.js"></script>
</body>
</html>