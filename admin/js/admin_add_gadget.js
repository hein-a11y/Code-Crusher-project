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
});