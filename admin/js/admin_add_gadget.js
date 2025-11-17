document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('specs-container');
    const addBtn = document.getElementById('add-spec-btn');

    // 行を追加する関数
    addBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'spec-row';
        div.innerHTML = `
            <input type="text" name="spec_name[]" class="form-input" placeholder="項目名" style="flex: 2;">
            <input type="text" name="spec_value[]" class="form-input" placeholder="値" style="flex: 2;">
            <input type="text" name="spec_unit[]" class="form-input" placeholder="単位" style="flex: 1;">
            <button type="button" class="remove-spec-btn" title="削除">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // 行を削除する処理 (イベント委譲)
    container.addEventListener('click', function(e) {
        // クリックされた要素が .remove-spec-btn またはその子要素(アイコン)の場合
        const btn = e.target.closest('.remove-spec-btn');
        if (btn) {
            const row = btn.closest('.spec-row');
            if (row) {
                row.remove();
            }
        }
    });
});