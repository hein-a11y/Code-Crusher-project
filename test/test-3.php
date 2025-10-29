<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>データ取得</title>
</head>
<body>
    <h1>ユーザー検索</h1>
    <input type="text" id="searchInput" placeholder="ユーザー名を入力">
    <button id="searchButton">検索</button>
    <div id="results"></div>
    
    <script>
        async function searchUsers() {
            const keyword = document.getElementById('searchInput').value;
            const resultsDiv = document.getElementById('results'); // 結果表示エリア
            
            // URLパラメータを作成
            const params = new URLSearchParams({
                keyword: keyword
            });
            
            try {
                // PHPファイル（search_users.php）にリクエスト
                const response = await fetch(`./search_users.php?${params}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json(); // PHPからのJSON応答
                
                // PHPから返された 'status' に応じて処理を分岐
                
                switch (result.status) {
                    case 'redirect':
                        // リダイレクト指示があった場合
                        if (result.method === 'GET') {
                            // GETならそのままページ遷移
                            window.location.href = result.action;
                        } else {
                            // POSTならフォームを作成して送信
                            const form = document.createElement('form');
                            form.method = result.method;
                            form.action = result.action;
                            document.body.appendChild(form);
                            form.submit();
                        }
                        break;

                    case 'not_found':
                        // 'not_found' (見つからない) メッセージを表示
                        resultsDiv.innerHTML = `<p>${result.message}</p>`;
                        break;
                    
                    case 'error':
                        // 'error' (キーワードなし等) メッセージを赤字で表示
                        resultsDiv.innerHTML = `<p style="color: red;">${result.message}</p>`;
                        break;

                    default:
                        // PHPが 'success' や予期しないstatusを返した場合
                        resultsDiv.innerHTML = '<p style="color: red;">予期しない応答がありました。</p>';
                }
                // --- 修正箇所ここまで ---
                
            } catch (error) {
                // fetch失敗やJSONパース失敗の場合
                resultsDiv.innerHTML = 
                    `<p style="color: red;">エラー: ${error.message}</p>`;
            }
        }

        // (ボタンクリックとEnterキーのイベントリスナーはそのまま)
        document.getElementById('searchButton').addEventListener('click', searchUsers);
        document.getElementById('searchInput').addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                searchUsers();
            }
        });
    </script>
</body>
</html>