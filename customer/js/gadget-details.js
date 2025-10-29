// サムネイルとカラー選択のインタラクティブ機能

document.addEventListener('DOMContentLoaded', () => {
    // --- 画像ギャラリー機能 ---
    const mainImage = document.getElementById('mainImage');
    const thumbnailContainer = document.getElementById('thumbnailContainer');
    
    // thumbnailContainer が存在する場合のみ処理
    if (thumbnailContainer) {
        thumbnailContainer.addEventListener('click', (e) => {
            const button = e.target.closest('.thumb-btn');
            if (!button) return;

            // メイン画像を更新
            mainImage.src = button.dataset.src;

            // active クラスを付け替え
            thumbnailContainer.querySelectorAll('.thumb-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    }

    // --- カラー選択機能 ---
    const selectedColor = document.getElementById('selectedColor');
    const colorSwatchContainer = document.getElementById('colorSwatchContainer');

    // colorSwatchContainer が存在する場合のみ処理
    if (colorSwatchContainer) {
        colorSwatchContainer.addEventListener('click', (e) => {
            const button = e.target.closest('.color-swatch');
            if (!button) return;

            // カラー名を更新
            selectedColor.textContent = button.dataset.color;

            // active クラスを付け替え
            colorSwatchContainer.querySelectorAll('.color-swatch').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    }
    // 初期表示で最初のサムネイルをメイン画像に設定
    const firstActiveThumb = thumbnailContainer.querySelector('.thumb-btn.active');
        if (firstActiveThumb) {
            mainImage.src = firstActiveThumb.dataset.src;
        }
});