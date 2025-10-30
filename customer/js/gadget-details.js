// サムネイル画像切り替えスクリプト
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('.thumbnail');

    // メイン画像が存在しない場合は処理を中断
    if (!mainImage) {
        console.error('Main image element not found.');
        return;
    }

    // サムネイルが1つも存在しない場合は処理を中断
    if (thumbnails.length === 0) {
        console.warn('No thumbnail elements found.');
        return;
    }

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // 他のサムネイルから 'active' クラスを削除
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // クリックされたサムネイルに 'active' クラスを追加
            this.classList.add('active');
            
            // メイン画像を切り替え (data-src属性が存在する場合のみ)
            if (this.dataset.src) {
                mainImage.src = this.dataset.src;
            }
        });
    });
});