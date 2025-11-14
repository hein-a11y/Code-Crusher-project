// HTMLがすべて読み込まれたら実行
document.addEventListener('DOMContentLoaded', () => {

    // ---
    // 1. 合計金額を「再計算＆書き換え」する関数
    // ---
    function updateTotalPrice() {
        let subtotal = 0;
        const shippingFee = 500; // PHP側と送料を合わせる

        // ページ上の「.cart-item」をすべてループ
        document.querySelectorAll('.cart-item').forEach(item => {
            // HTMLに埋め込んだ「単価 (data-price)」を取得
            const price = parseFloat(item.dataset.price);
            
            // その商品の「現在の数量 (value)」を取得
            const quantityInput = item.querySelector('.item-quantity');
            const quantity = parseInt(quantityInput.value);

            // もし数量が無効な値（空や0）なら、1として計算
            if (isNaN(quantity) || quantity < 1) {
                subtotal += price; // 数量1として加算
            } else {
                subtotal += price * quantity; // 小計に加算 (単価 × 数量)
            }
        });

        const grandTotal = subtotal + shippingFee;

        // HTMLの id="..." の場所を「書き換える」
        // toLocaleString() は数値にカンマ(,)を付ける
        const subtotalDisplay = document.getElementById('subtotal-display');
        const grandtotalDisplay = document.getElementById('grandtotal-display');

        if (subtotalDisplay) {
            subtotalDisplay.textContent = '¥' + subtotal.toLocaleString();
        }
        if (grandtotalDisplay) {
            grandtotalDisplay.textContent = '¥' + grandTotal.toLocaleString();
        }
    }

    // ---
    // 2. 「数量」が変更された時の監視
    // ---
    const quantityInputs = document.querySelectorAll('.item-quantity');
    quantityInputs.forEach(input => {
        // 'input' イベント = 値が変更されるたびに
        input.addEventListener('input', () => {
            // もし入力が 1 未満なら 1 に強制
            if (input.value < 1) {
                input.value = 1;
            }
            // 合計を再計算
            updateTotalPrice();
        });
    });

    // ---
    // 3. 「削除」リンクがクリックされた時の監視
    // ---
    const removeLinks = document.querySelectorAll('.remove-link');
    removeLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            // リンクのデフォルト動作（ページ遷移）を止める
            event.preventDefault();

            // 削除確認
            if (!confirm('この商品をカートから削除しますか？')) {
                return; // 「キャンセル」が押されたら何もしない
            }

            // (A) PHPに削除を伝えるカートIDを取得
            const cartId = link.dataset.cartId;
            // (B) HTMLから消すための親要素を取得
            const itemElement = link.closest('.cart-item');

            // (C) サーバー（PHP）に削除をリクエスト
            fetch('delete-cart.php', {
                method: 'POST',
                headers: {
                    // フォーム形式でデータを送る
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                // 'cart_id=...' という文字列でIDを送る
                body: 'cart_id=' + cartId
            })
            .then(response => response.json()) // PHPからの返事をJSONとして解釈
            .then(data => {
                // (D) PHP側で削除が成功したら
                if (data.success) {
                    // (E) 画面（HTML）からその商品を消す
                    itemElement.remove();
                    // (F) 合計金額を再計算
                    updateTotalPrice();
                } else {
                    // (G) PHP側で失敗したらエラーを出す
                    alert('削除に失敗しました。');
                }
            })
            .catch(error => {
                // ネットワークエラーなど
                console.error('削除処理エラー:', error);
                alert('削除中にエラーが発生しました。');
            });
        });
    });

});