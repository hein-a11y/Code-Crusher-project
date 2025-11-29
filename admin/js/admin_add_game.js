// PHPから渡された過去のスペック値データ
const specValuesMap = window.specValuesMap || {};

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
    const specNameTemplateEl = document.getElementById('spec-name-options');
    const specNameTemplate = specNameTemplateEl ? specNameTemplateEl.innerHTML : '';

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
    const addMinBtn = document.getElementById('add-min-spec-btn');
    const addRecBtn = document.getElementById('add-rec-spec-btn');
    
    if (addMinBtn) addMinBtn.addEventListener('click', () => addSpecRow('min-specs-container', 'min_req'));
    if (addRecBtn) addRecBtn.addEventListener('click', () => addSpecRow('rec-specs-container', 'rec_req'));

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
