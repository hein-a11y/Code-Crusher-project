<?php require_once '../header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
    <style>

        /* --------------------------------
         * 検索結果なしのメッセージ
         * -------------------------------- */
        .no-results-container {
            /* 横幅が広がりすぎないよう設定し、中央に配置 */
            max-width: 700px;
            margin: 40px auto; /* 上下に余白、左右は自動で中央 */
            padding: 20px;
        }

        /* 1行目: 見出し */
        .no-results-container h3 {
            font-size: 1.5rem; /* 約24px */
            font-weight: 600;
            color: var(--texty-color); /* 濃いグレー（ほぼ黒） */
            margin-top: 0;
            margin-bottom: 16px;
            padding-bottom: 8px;
        }

        /* 2行目・3行目: 本文 */
        .no-results-container p {
            font-size: 0.9rem; /* 約14.4px */
            color: var(--text-secondary-color); /* 見出しより少し薄いグレー */
            margin-top: 0;
            margin-bottom: 12px;
        }

        /* 最後の段落の下マージンは不要にする */
        .no-results-container p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <div class="no-results-container">
        
        <h3>検索クエリの結果はありません</h3>
        
        <p>キーワードが正しく入力されていても一致する商品がない場合は、別の言葉をお試しください。</p>
        
        <p>その他の購入オプションについては、各商品詳細ページをご確認ください。</p>

    </div>
    </body>
</html>


