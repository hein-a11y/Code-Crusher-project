document.addEventListener('DOMContentLoaded', function() {
    
    const container = document.getElementById('specs-container');
    const addBtn = document.getElementById('add-spec-btn');

    // -------------------------------------------------------
    // 1. 単位の自動入力ロジック (イベント委譲)
    // -------------------------------------------------------
    if (container) {
        container.addEventListener('change', function(e) {
            // 変更された要素がセレクトボックス(.spec-select)か確認
            if (e.target.classList.contains('spec-select')) {
                const selectBox = e.target;
                
                // 同じ行にある単位入力欄(.spec-unit)を探す
                const row = selectBox.closest('.spec-row');
                const unitInput = row.querySelector('.spec-unit');
                
                // 選択されたoptionのdata-unit属性を取得
                const selectedOption = selectBox.options[selectBox.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit');
                
                // 単位入力欄に値をセット (値がない場合は空文字)
                if (unitInput) {
                    unitInput.value = unit ? unit : '';
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
        // これによりDBから取得した最新の選択肢をそのまま新しい行に使えます
        const firstSelectHTML = container.querySelector('select[name="spec_name[]"]').innerHTML;

        addBtn.addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'spec-row';
            div.innerHTML = `
                <select name="spec_name[]" class="form-select flex-grow-2 spec-select">
                    ${firstSelectHTML}
                </select>
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