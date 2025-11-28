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

<style>
    /* --- 基本設定 --- */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

    /* --- レイアウト調整 --- */
    .custom-input-wrapper {
        display: none;
        margin-top: 10px;
    }

    /* --- チェックボックスリスト (ジャンル用) --- */
    .checkbox-list {
        background-color: #333; /* フォーム背景色に合わせる */
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.75rem;
        max-height: 150px; /* 長くなりすぎないようスクロールさせる */
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-size: 0.875rem;
        color: var(--text-primary);
    }
    .checkbox-item input[type="checkbox"] {
        accent-color: var(--accent-blue); /* テーマカラーに合わせる */
        width: 1rem;
        height: 1rem;
    }

    /* --- スペック入力エリア --- */
    .spec-section {
        margin-bottom: 2rem;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: rgba(255, 255, 255, 0.02);
    }
    .spec-section h4 {
        margin-bottom: 1rem;
        color: var(--accent-blue);
        font-size: 0.95rem;
    }

    .spec-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        align-items: flex-start;
    }
    .spec-input-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .spec-custom-input,
    .spec-value-custom-input {
        display: none;
    }
    
    /* ボタン類 */
    .remove-spec-btn {
        color: var(--red);
        width: 30px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background-color: rgba(248, 113, 113, 0.1);
        transition: background-color 0.3s;
        cursor: pointer;
        border: none;
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
        padding: 0.5rem;
    }
    .button-full { width: 100%; }

    /* --- 画像アップロード --- */
    .form-help-text {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 10px;
    }
    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    @media (min-width: 1200px) {
        .image-grid {
             grid-template-columns: repeat(9, 1fr);
        }
    }
    .image-slot {
        aspect-ratio: 1 / 1;
        background-color: #ebf0f3;
        border: 1px solid #ccc;
        border-radius: 4px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s, border-color 0.2s;
        overflow: hidden;
    }
    .image-slot:hover {
        background-color: #dbe4e9;
    }
    .image-slot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .slot-placeholder {
        text-align: center;
        color: #333;
        font-size: 11px;
        font-weight: bold;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
    }
    .slot-placeholder i {
        font-size: 24px;
        margin-bottom: 5px;
        color: #333;
    }
    .slot-delete-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 20px;
        height: 20px;
        background-color: rgba(0, 0, 0, 0.6);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        z-index: 10;
        transition: background-color 0.2s;
    }
    .slot-delete-btn:hover {
        background-color: #ff4444;
    }
    .main-image-label {
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        text-align: left;
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

<script src="./js/admin_add_gadget.js"></script>

<script>
// PHPから渡された過去のスペック値データ
const specValuesMap = <?php echo $json_spec_values; ?>;

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. プラットフォーム「その他」切り替え ---
    const platformSelect = document.getElementById('platform_id');
    const newPlatformWrapper = document.getElementById('new_platform_wrapper');
    const newPlatformInput = document.getElementById('new_platform');

    if (platformSelect) {
        platformSelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newPlatformWrapper.style.display = 'block';
                newPlatformInput.required = true;
            } else {
                newPlatformWrapper.style.display = 'none';
                newPlatformInput.required = false;
                newPlatformInput.value = '';
            }
        });
    }

    // --- 2. ジャンル「その他」切り替え (チェックボックス版) ---
    const genreOtherCheckbox = document.getElementById('genre_other');
    const newGenreWrapper = document.getElementById('new_genre_wrapper');
    const newGenreInput = document.getElementById('new_genre');

    if (genreOtherCheckbox) {
        genreOtherCheckbox.addEventListener('change', function() {
            if (this.checked) {
                newGenreWrapper.style.display = 'block';
                newGenreInput.required = true;
            } else {
                newGenreWrapper.style.display = 'none';
                newGenreInput.required = false;
                newGenreInput.value = '';
            }
        });
    }

    // --- 3. スペック行の動的追加と連動ロジック ---
    const specNameTemplate = document.getElementById('spec-name-options').innerHTML;

    function addSpecRow(containerId, inputNamePrefix) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const div = document.createElement('div');
        div.className = 'spec-row';
        
        div.innerHTML = `
            <div class="spec-input-wrapper">
                <select name="${inputNamePrefix}_id[]" class="form-select spec-name-select" style="width: 100%;">
                    ${specNameTemplate}
                </select>
                <input type="text" name="${inputNamePrefix}_custom[]" class="form-input spec-custom-input" placeholder="項目名を入力">
            </div>
            
            <div class="spec-input-wrapper">
                <select name="${inputNamePrefix}_value_select[]" class="form-select spec-value-select" style="width: 100%;">
                    <option value="new">その他 (手入力)</option>
                </select>
                <input type="text" name="${inputNamePrefix}_value_custom[]" class="form-input spec-value-custom-input" placeholder="値を入力" style="display:block;">
            </div>

            <button type="button" class="remove-spec-btn" title="削除">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(div);
    }

    // ボタンイベント
    document.getElementById('add-min-spec-btn').addEventListener('click', () => addSpecRow('min-specs-container', 'min_req'));
    document.getElementById('add-rec-spec-btn').addEventListener('click', () => addSpecRow('rec-specs-container', 'rec_req'));

    // 初期表示 (各1行)
    addSpecRow('min-specs-container', 'min_req');
    addSpecRow('rec-specs-container', 'rec_req');


    // --- 4. スペックエリアのイベント委譲 ---
    document.addEventListener('change', function(e) {
        const target = e.target;

        // A. スペック項目名変更
        if (target.classList.contains('spec-name-select')) {
            const row = target.closest('.spec-row');
            const nameWrapper = target.closest('.spec-input-wrapper');
            const nameCustomInput = nameWrapper.querySelector('.spec-custom-input');

            // 「その他」表示
            if (target.value === 'other') {
                nameCustomInput.style.display = 'block';
                nameCustomInput.required = true;
            } else {
                nameCustomInput.style.display = 'none';
                nameCustomInput.required = false;
                nameCustomInput.value = '';
            }

            // スペック値のセレクトボックスを更新
            const valSelect = row.querySelector('.spec-value-select');
            const specId = target.value;
            
            valSelect.innerHTML = ''; // クリア

            if (specId && specValuesMap[specId]) {
                const defaultOpt = document.createElement('option');
                defaultOpt.value = "";
                defaultOpt.text = "値を選択...";
                valSelect.appendChild(defaultOpt);

                specValuesMap[specId].forEach(val => {
                    const opt = document.createElement('option');
                    opt.value = val;
                    opt.text = val;
                    valSelect.appendChild(opt);
                });
            }

            const otherOpt = document.createElement('option');
            otherOpt.value = "new";
            otherOpt.text = "その他 (手入力)";
            if (!specId || !specValuesMap[specId]) {
                otherOpt.selected = true;
            }
            valSelect.appendChild(otherOpt);

            valSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // B. スペック値変更
        if (target.classList.contains('spec-value-select')) {
            const valWrapper = target.closest('.spec-input-wrapper');
            const valCustomInput = valWrapper.querySelector('.spec-value-custom-input');
            
            if (target.value === 'new') {
                valCustomInput.style.display = 'block';
            } else {
                valCustomInput.style.display = 'none';
                valCustomInput.value = '';
            }
        }
    });

    // 削除ボタン
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-spec-btn');
        if (btn) {
            btn.closest('.spec-row').remove();
        }
    });

    // 画像アップロード処理
    const fileInput = document.getElementById('game_images');
    const gridContainer = document.getElementById('image-grid-container');
    const dataTransfer = new DataTransfer();
    const MAX_IMAGES = 9;

    if (gridContainer && fileInput) {
        renderGrid();
        fileInput.addEventListener('change', () => handleFiles(fileInput.files));

        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                if (dataTransfer.items.length >= MAX_IMAGES) break;
                if (!files[i].type.startsWith('image/')) continue;
                dataTransfer.items.add(files[i]);
            }
            fileInput.files = dataTransfer.files;
            renderGrid();
        }

        window.removeGameFileAtIndex = function(index) {
            const newDataTransfer = new DataTransfer();
            const currentFiles = dataTransfer.files;
            for (let i = 0; i < currentFiles.length; i++) {
                if (i !== index) newDataTransfer.items.add(currentFiles[i]);
            }
            dataTransfer.items.clear();
            for (let i = 0; i < newDataTransfer.files.length; i++) dataTransfer.items.add(newDataTransfer.files[i]);
            fileInput.files = dataTransfer.files;
            renderGrid();
        };

        function renderGrid() {
            gridContainer.innerHTML = '';
            const files = dataTransfer.files;
            for (let i = 0; i < MAX_IMAGES; i++) {
                const slot = document.createElement('div');
                slot.className = 'image-slot';
                if (files[i]) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(files[i]);
                    const deleteBtn = document.createElement('div');
                    deleteBtn.className = 'slot-delete-btn';
                    deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
                    deleteBtn.onclick = (e) => { e.stopPropagation(); removeGameFileAtIndex(i); };
                    slot.appendChild(img);
                    slot.appendChild(deleteBtn);
                } else {
                    slot.innerHTML = '<div class="slot-placeholder"><i class="fas fa-camera"></i><span>アップロード</span></div>';
                    slot.addEventListener('click', () => fileInput.click());
                    slot.addEventListener('dragover', (e) => { e.preventDefault(); slot.classList.add('dragover'); });
                    slot.addEventListener('dragleave', () => slot.classList.remove('dragover'));
                    slot.addEventListener('drop', (e) => { e.preventDefault(); slot.classList.remove('dragover'); handleFiles(e.dataTransfer.files); });
                }
                if (i === 0) {
                    const label = document.createElement('div');
                    label.className = 'main-image-label';
                    label.innerText = 'メイン画像';
                    slot.appendChild(label);
                }
                gridContainer.appendChild(slot);
            }
        }
    }
});
</script>
</body>
</html>