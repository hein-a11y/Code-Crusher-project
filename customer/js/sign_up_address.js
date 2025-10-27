// 住所検索を実行する関数
function fetchAddress() {
    // HTMLから要素を取得
    const postalCodeInput = document.getElementById('postal-code');
    const prefectureInput = document.getElementById('prefecture');
    const cityInput = document.getElementById('city');
    const address_line1Input = document.getElementById('address_line1');
    const address_line2Input = document.getElementById('address_line2');
    
    const errorMessage = document.getElementById('error-message');

    const postalCode = postalCodeInput.value;

    errorMessage.textContent = '';
    prefectureInput.value = '';
    cityInput.value = '';
    address_line1Input.value = '';
    address_line2Input.value = '';

    const url = `https://zipcloud.ibsnet.co.jp/api/search?zipcode=${postalCode}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('ネットワークの接続に問題があります。');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 200) {
                if (data.results) {
                    const result = data.results[0];
                    
                    prefectureInput.value = result.address1;
                    cityInput.value = result.address2 + result.address3;
                    
                    address_line1Input.focus(); // 番地欄にフォーカス (変更なし)
                } else {
                    errorMessage.textContent = '該当する住所が見つかりません。';
                }
            } else {
                errorMessage.textContent = '住所の取得に失敗しました: ' + data.message;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            errorMessage.textContent = '通信エラーが発生しました。';
        });
}

// 7桁入力されたら自動検索するイベント (変更なし)
document.getElementById('postal-code').addEventListener('input', (event) => {
    if (event.target.value.length === 7) {
        fetchAddress(); 
    }
});