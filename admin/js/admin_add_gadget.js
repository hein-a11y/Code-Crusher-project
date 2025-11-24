document.addEventListener('DOMContentLoaded', function() {
    
    const container = document.getElementById('specs-container');
    const addBtn = document.getElementById('add-spec-btn');

    // -------------------------------------------------------
    // 1. スペックのイベントロジック (単位自動入力 & その他表示)
    // -------------------------------------------------------
    if (container) {
        container.addEventListener('change', function(e) {
            // 変更された要素がセレクトボックス(.spec-select)か確認
            if (e.target.classList.contains('spec-select')) {
                const selectBox = e.target;
                const row = selectBox.closest('.spec-row');
                
                // --- A. 単位の自動入力 ---
                const unitInput = row.querySelector('.spec-unit');
                const selectedOption = selectBox.options[selectBox.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit');
                
                // 単位入力欄に値をセット (値がない場合は空文字)
                if (unitInput) {
                    // 単位が設定されていれば入力、なければ空にする（手動入力可能）
                    if (unit) unitInput.value = unit;
                }

                // --- B. 「その他」入力欄の表示切り替え ---
                // セレクトボックスの親ラッパー内のカスタム入力を探す
                const wrapper = selectBox.closest('.spec-input-wrapper');
                const customInput = wrapper.querySelector('.spec-custom-input');

                if (customInput) {
                    if (selectBox.value === 'other') {
                        customInput.style.display = 'block';
                        customInput.required = true; // 必須にする
                    } else {
                        customInput.style.display = 'none';
                        customInput.required = false;
                        customInput.value = ''; // 入力をクリア
                    }
                }
            }
        });

        // -------------------------------------------------------
        // 2. 削除ボタンのロジック (イベント委譲)
        // -------------------------------------------------------
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-spec-btn');
            if (btn) {
                const row = btn.closest('.spec-row');
                if (row) {
                    row.remove();
                }
            }
        });
    }

    // -------------------------------------------------------
    // 3. スペック行の追加ロジック
    // -------------------------------------------------------
    if (container && addBtn) {
        // 最初の行のセレクトボックスの中身(HTML)をコピーしておく
        const firstSelectHTML = container.querySelector('select[name="spec_name[]"]').innerHTML;

        addBtn.addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'spec-row';
            
            // ラッパーとカスタム入力を含んだ構造で追加
            div.innerHTML = `
                <div class="spec-input-wrapper">
                    <select name="spec_name[]" class="form-select spec-select" style="width: 100%;">
                        ${firstSelectHTML}
                    </select>
                    <input type="text" name="spec_custom_name[]" class="form-input spec-custom-input" placeholder="項目名を入力">
                </div>
                <input type="text" name="spec_value[]" class="form-input flex-grow-2" placeholder="値">
                <input type="text" name="spec_unit[]" class="form-input flex-grow-1 spec-unit" placeholder="単位">
                <button type="button" class="remove-spec-btn" title="削除">
                    <i class="fas fa-minus"></i>
                </button>
            `;
            container.appendChild(div);
        });
    }

    // --- カテゴリ「その他」選択時の入力フィールド表示ロジック ---
    const categorySelect = document.getElementById('category_id');
    const newCategoryWrapper = document.getElementById('new_category_wrapper');
    const newCategoryInput = document.getElementById('new_category');

    if (categorySelect && newCategoryWrapper && newCategoryInput) {
        categorySelect.addEventListener('change', function() {
            if (this.value === 'new') {
                newCategoryWrapper.style.display = 'block';
                newCategoryInput.required = true;
            } else {
                newCategoryWrapper.style.display = 'none';
                newCategoryInput.required = false;
                newCategoryInput.value = '';
            }
        });
    }

    // ==========================================
    //  1. 画像アップロード処理 (グリッド形式)
    // ==========================================
    const fileInput = document.getElementById('gadget_images');
    const gridContainer = document.getElementById('image-grid-container');
    const dataTransfer = new DataTransfer(); // ファイル管理用
    const MAX_IMAGES = 9;

    if (gridContainer && fileInput) {
        
        // 初期描画
        renderGrid();

        // ファイル選択後の処理
        fileInput.addEventListener('change', () => {
            handleFiles(fileInput.files);
        });

        // ファイル追加処理
        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                // 最大枚数チェック
                if (dataTransfer.items.length >= MAX_IMAGES) break;
                
                const file = files[i];
                // 画像のみ許可
                if (!file.type.startsWith('image/')) continue;
                
                dataTransfer.items.add(file);
            }
            // inputを更新して再描画
            fileInput.files = dataTransfer.files;
            renderGrid();
        }

        // ファイル削除処理
        window.removeFileAtIndex = function(index) {
            const newDataTransfer = new DataTransfer();
            const currentFiles = dataTransfer.files;

            for (let i = 0; i < currentFiles.length; i++) {
                if (i !== index) {
                    newDataTransfer.items.add(currentFiles[i]);
                }
            }
            // データ更新
            dataTransfer.items.clear();
            for (let i = 0; i < newDataTransfer.files.length; i++) {
                dataTransfer.items.add(newDataTransfer.files[i]);
            }
            
            fileInput.files = dataTransfer.files;
            renderGrid();
        };

        // グリッド描画関数 (常に9枠表示)
        function renderGrid() {
            gridContainer.innerHTML = '';
            const files = dataTransfer.files;

            for (let i = 0; i < MAX_IMAGES; i++) {
                const slot = document.createElement('div');
                slot.className = 'image-slot';
                
                // ファイルが存在する場合（プレビュー表示）
                if (files[i]) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(files[i]);
                    
                    const deleteBtn = document.createElement('div');
                    deleteBtn.className = 'slot-delete-btn';
                    deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
                    deleteBtn.onclick = (e) => {
                        e.stopPropagation(); // 親のクリックイベントを止める
                        removeFileAtIndex(i);
                    };

                    slot.appendChild(img);
                    slot.appendChild(deleteBtn);
                } 
                // ファイルがない場合（アップロードボタン表示）
                else {
                    slot.innerHTML = `
                        <div class="slot-placeholder">
                            <i class="fas fa-camera"></i>
                            <span>アップロードする</span>
                        </div>
                    `;
                    
                    // クリックでファイル選択
                    slot.addEventListener('click', () => fileInput.click());

                    // ドラッグ＆ドロップイベント
                    slot.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        slot.classList.add('dragover');
                    });
                    slot.addEventListener('dragleave', () => {
                        slot.classList.remove('dragover');
                    });
                    slot.addEventListener('drop', (e) => {
                        e.preventDefault();
                        slot.classList.remove('dragover');
                        handleFiles(e.dataTransfer.files);
                    });
                }

                // 1つ目の枠には「メイン画像」ラベルを表示
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