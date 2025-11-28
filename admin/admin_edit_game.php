<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<?php
// IDがない場合は一覧へ戻す
$game_id = $_GET['id'] ?? null;
if (!$game_id) {
    header('Location: admin_products.php');
    exit;
}

$pdo = getPDO();

// 1. ゲーム本体データの取得
$sql = $pdo->prepare("SELECT * FROM gg_game WHERE game_id = ?");
$sql->execute([$game_id]);
$game = $sql->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    die("指定されたゲームが見つかりません。");
}

// 2. 登録済みジャンルIDの取得
$sql = $pdo->prepare("SELECT genre_id FROM gg_game_genres WHERE game_id = ?");
$sql->execute([$game_id]);
$current_genres = $sql->fetchAll(PDO::FETCH_COLUMN); // [1, 3, 5] のような配列になる

// 3. 動作環境(スペック)の取得
$sql = $pdo->prepare("SELECT * FROM gg_game_requirements WHERE game_id = ? ORDER BY game_req_id ASC");
$sql->execute([$game_id]);
$current_specs = $sql->fetchAll(PDO::FETCH_ASSOC);

// スペックをMINとRECに振り分け
$min_specs = array_filter($current_specs, function($s) { return $s['type'] === 'MIN'; });
$rec_specs = array_filter($current_specs, function($s) { return $s['type'] === 'REC'; });

// 4. スペック値の候補（オートコンプリート用）
$spec_values_map = [];
try {
    $sql = $pdo->query("SELECT spec_id, spec_value FROM gg_game_requirements GROUP BY spec_id, spec_value ORDER BY spec_value ASC");
    foreach ($sql as $row) {
        $spec_values_map[$row['spec_id']][] = h($row['spec_value']);
    }
} catch (Exception $e) {}
$json_spec_values = json_encode($spec_values_map);
?>

<style>
    /* admin_add_game.php と同様のスタイル + 追記 */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .custom-input-wrapper { display: none; margin-top: 10px; }
    .spec-section { margin-bottom: 2rem; padding: 1rem; border: 1px solid var(--border-color); border-radius: 8px; background-color: rgba(255, 255, 255, 0.02); }
    .spec-section h4 { margin-bottom: 1rem; color: var(--accent-blue); font-size: 0.95rem; }
    .spec-row { display: flex; gap: 10px; margin-bottom: 10px; align-items: flex-start; }
    .spec-input-wrapper { flex: 1; display: flex; flex-direction: column; gap: 5px; }
    .spec-custom-input, .spec-value-custom-input { display: none; }
    .remove-spec-btn { color: var(--red); width: 30px; height: 38px; display: flex; align-items: center; justify-content: center; border-radius: 4px; background-color: rgba(248, 113, 113, 0.1); border: none; cursor: pointer; }
    .remove-spec-btn:hover { background-color: rgba(248, 113, 113, 0.2); }
    .button-dashed { width: 100%; margin-top: 10px; border-style: dashed; border-width: 1px; border-color: var(--border-color); padding: 0.5rem; }
    .button-full { width: 100%; }
    
    /* チェックボックスリスト */
    .checkbox-list { background-color: #333; border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 0.75rem; max-height: 150px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.5rem; }
    .checkbox-item { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem; color: var(--text-primary); }
    .checkbox-item input[type="checkbox"] { accent-color: var(--accent-blue); width: 1rem; height: 1rem; }
</style>

<div class="main-content">
    <header class="header">
        <button id="sidebar-toggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
        <div class="header-search"></div>
    </header>

    <main class="page-content">
        <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

        <section class="admin-page">
            <h2 class="page-title">ゲーム編集: <?php echo h($game['game_name']); ?></h2>
            
            <form action="./game_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="game_id" value="<?php echo h($game['game_id']); ?>">

                <div class="card">
                    <h3 class="card-title">基本情報</h3>
                    
                    <div class="form-group">
                        <label for="game_name" class="form-label">ゲームタイトル</label>
                        <input type="text" id="game_name" name="game_name" class="form-input" required value="<?php echo h($game['game_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="game_explanation" class="form-label">ゲーム説明</label>
                        <textarea id="game_explanation" name="game_explanation" class="form-textarea"><?php echo h($game['game_explanation']); ?></textarea>
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
                                    $selected = ($id == $game['platform_id']) ? 'selected' : '';
                                    echo <<<HTML
                                    <option value="{$id}" {$selected}>{$name}</option>
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
                                    // 既に登録されているジャンルならチェックを入れる
                                    $checked = in_array($id, $current_genres) ? 'checked' : '';
                                    echo <<<HTML
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="genre_ids[]" value="{$id}" {$checked}>
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
                            <input type="text" id="manufacturer" name="manufacturer" class="form-input" value="<?php echo h($game['manufacturer']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="game_type" class="form-label">販売形式</label>
                            <select id="game_type" name="game_type" class="form-select">
                                <option value="Physical" <?php echo ($game['game_type'] == 'Physical') ? 'selected' : ''; ?>>パッケージ版 (Physical)</option>
                                <option value="Digital" <?php echo ($game['game_type'] == 'Digital') ? 'selected' : ''; ?>>デジタル版 (Digital)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="price" class="form-label">価格 (円)</label>
                            <input type="number" id="price" name="price" class="form-input" required value="<?php echo h($game['price']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="stock" class="form-label">在庫数</label>
                            <input type="number" id="stock" name="stock" class="form-input" value="<?php echo h($game['stock']); ?>">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="card-title">システム要件</h3>

                    <template id="spec-name-options">
                        <option value="">項目を選択...</option>
                        <?php
                        $spec_options = $pdo->query("SELECT * FROM gg_specifications WHERE product_type IN ('GAME', 'BOTH') ORDER BY spec_id ASC")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($spec_options as $opt) {
                            echo <<<HTML
                            <option value="{$opt['spec_id']}">{$opt['spec_name']}</option>
                            HTML;
                        }
                        ?>
                        <option value="other">その他</option>
                    </template>

                    <div class="spec-section">
                        <h4>最低動作環境 (Minimum)</h4>
                        <div id="min-specs-container">
                            <?php foreach ($min_specs as $spec): ?>
                                <div class="spec-row">
                                    <div class="spec-input-wrapper">
                                        <select name="min_req_id[]" class="form-select spec-name-select" style="width: 100%;">
                                            <option value="">項目を選択...</option>
                                            <?php foreach ($spec_options as $opt): ?>
                                                <?php $selected = ($opt['spec_id'] == $spec['spec_id']) ? 'selected' : ''; ?>
                                                <option value="<?= $opt['spec_id'] ?>" <?= $selected ?>>
                                                    <?= h($opt['spec_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="other">その他</option>
                                        </select>
                                        <input type="text" name="min_req_custom[]" class="form-input spec-custom-input" placeholder="項目名">
                                    </div>
                                    <div class="spec-input-wrapper">
                                        <select name="min_req_value_select[]" class="form-select spec-value-select" style="width: 100%;">
                                            <option value="<?= h($spec['spec_value']) ?>" selected><?= h($spec['spec_value']) ?></option>
                                            <option value="new">その他 (手入力)</option>
                                        </select>
                                        <input type="text" name="min_req_value_custom[]" class="form-input spec-value-custom-input" placeholder="値">
                                    </div>
                                    <button type="button" class="remove-spec-btn"><i class="fas fa-minus"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-min-spec-btn" class="button button-secondary button-dashed"><i class="fas fa-plus"></i> 追加</button>
                    </div>

                    <div class="spec-section">
                        <h4>推奨動作環境 (Recommended)</h4>
                        <div id="rec-specs-container">
                            <?php foreach ($rec_specs as $spec): ?>
                                <div class="spec-row">
                                    <div class="spec-input-wrapper">
                                        <select name="rec_req_id[]" class="form-select spec-name-select" style="width: 100%;">
                                            <option value="">項目を選択...</option>
                                            <?php foreach ($spec_options as $opt): ?>
                                                <?php $selected = ($opt['spec_id'] == $spec['spec_id']) ? 'selected' : ''; ?>
                                                <option value="<?= $opt['spec_id'] ?>" <?= $selected ?>>
                                                    <?= h($opt['spec_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="other">その他</option>
                                        </select>
                                        <input type="text" name="rec_req_custom[]" class="form-input spec-custom-input" placeholder="項目名">
                                    </div>
                                    <div class="spec-input-wrapper">
                                        <select name="rec_req_value_select[]" class="form-select spec-value-select" style="width: 100%;">
                                            <option value="<?= h($spec['spec_value']) ?>" selected><?= h($spec['spec_value']) ?></option>
                                            <option value="new">その他 (手入力)</option>
                                        </select>
                                        <input type="text" name="rec_req_value_custom[]" class="form-input spec-value-custom-input" placeholder="値">
                                    </div>
                                    <button type="button" class="remove-spec-btn"><i class="fas fa-minus"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-rec-spec-btn" class="button button-secondary button-dashed"><i class="fas fa-plus"></i> 追加</button>
                    </div>
                </div>

                <div class="card">
                    <button type="submit" class="button button-primary button-full">
                        <i class="fas fa-save"></i> 更新を保存する
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>

<script>
const specValuesMap = <?php echo $json_spec_values; ?>;

document.addEventListener('DOMContentLoaded', function() {
    // --- プラットフォーム・ジャンルの「その他」切り替え ---
    const platformSelect = document.getElementById('platform_id');
    const newPlatformWrapper = document.getElementById('new_platform_wrapper');
    const newPlatformInput = document.getElementById('new_platform');
    if (platformSelect) {
        platformSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newPlatformWrapper.style.display = 'block'; newPlatformInput.required = true;
            } else {
                newPlatformWrapper.style.display = 'none'; newPlatformInput.required = false;
            }
        });
    }

    const genreOtherCheckbox = document.getElementById('genre_other');
    const newGenreWrapper = document.getElementById('new_genre_wrapper');
    const newGenreInput = document.getElementById('new_genre');
    if (genreOtherCheckbox) {
        genreOtherCheckbox.addEventListener('change', function() {
            if (this.checked) {
                newGenreWrapper.style.display = 'block'; newGenreInput.required = true;
            } else {
                newGenreWrapper.style.display = 'none'; newGenreInput.required = false;
            }
        });
    }

    // --- スペック行追加 ---
    const specNameTemplate = document.getElementById('spec-name-options').innerHTML;
    function addSpecRow(containerId, prefix) {
        const div = document.createElement('div');
        div.className = 'spec-row';
        div.innerHTML = `
            <div class="spec-input-wrapper">
                <select name="${prefix}_id[]" class="form-select spec-name-select" style="width:100%">${specNameTemplate}</select>
                <input type="text" name="${prefix}_custom[]" class="form-input spec-custom-input" placeholder="項目名">
            </div>
            <div class="spec-input-wrapper">
                <select name="${prefix}_value_select[]" class="form-select spec-value-select" style="width:100%"><option value="new">その他 (手入力)</option></select>
                <input type="text" name="${prefix}_value_custom[]" class="form-input spec-value-custom-input" placeholder="値" style="display:block">
            </div>
            <button type="button" class="remove-spec-btn"><i class="fas fa-minus"></i></button>
        `;
        document.getElementById(containerId).appendChild(div);
    }
    document.getElementById('add-min-spec-btn').addEventListener('click', () => addSpecRow('min-specs-container', 'min_req'));
    document.getElementById('add-rec-spec-btn').addEventListener('click', () => addSpecRow('rec-specs-container', 'rec_req'));

    // --- イベント委譲 ---
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('spec-name-select')) {
            // スペック名変更時の値リスト更新
            const row = e.target.closest('.spec-row');
            const customInput = e.target.closest('.spec-input-wrapper').querySelector('.spec-custom-input');
            const valSelect = row.querySelector('.spec-value-select');
            const specId = e.target.value;

            if(specId === 'other') { customInput.style.display = 'block'; } else { customInput.style.display = 'none'; }

            valSelect.innerHTML = '';
            if(specId && specValuesMap[specId]) {
                valSelect.add(new Option("値を選択...", ""));
                specValuesMap[specId].forEach(val => valSelect.add(new Option(val, val)));
            }
            valSelect.add(new Option("その他 (手入力)", "new", true, true)); // デフォルトその他
            valSelect.dispatchEvent(new Event('change', {bubbles:true}));
        }
        if(e.target.classList.contains('spec-value-select')) {
            // 値の「その他」切り替え
            const customVal = e.target.closest('.spec-input-wrapper').querySelector('.spec-value-custom-input');
            if(e.target.value === 'new') customVal.style.display = 'block';
            else customVal.style.display = 'none';
        }
    });
    document.addEventListener('click', function(e) {
        if(e.target.closest('.remove-spec-btn')) e.target.closest('.spec-row').remove();
    });
});
</script>
</body>
</html>