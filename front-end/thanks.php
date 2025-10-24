<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了</title>

    <style>
        /* * -------------------
         * コンテナ (フォーム画面と共通)
         * -------------------
         */
        .form-container {
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            text-align: center;
            margin: 2rem auto; 
        }

        /* * -------------------
         * 送信完了メッセージ
         * -------------------
         */

        /* メッセージのタイトル */
        .thanks-message h2 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #e0e0e0;
        }

        /* メッセージの説明文 */
        .thanks-message p {
            margin-top: 0;
            margin-bottom: 2rem;
            color: #e0e0e0;
            line-height: 1.6;
        }

        /* * -------------------
         * 戻るボタン
         * -------------------
         */
        .back-button {
            display: inline-block; /* 中央揃えのため inline-block に変更 */
            padding: 12px 30px; /* 横幅を 100% にしないため、左右の padding を指定 */
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background-color: #00bfff; /* フォームのボタンと同じ色 */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* a タグのデフォルト下線を消す */
        }

        .back-button:hover {
            background-color: #0097ca;
        }
    </style>
</head>
<body>

    <div class="form-container">
        
        <div class="thanks-message">
            <h2>送信完了</h2>
            <p>
                お問い合わせいただき、誠にありがとうございました。<br>
                内容を確認の上、担当者よりご連絡いたします。
            </p>
        </div>

        <a href="form.php" class="back-button">
            トップページに戻る
        </a>

    </div>

</body>
</html>