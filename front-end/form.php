<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>

    <style>
        .form-container {
            background-color: #333;
            border-radius: 8px; /* 角の丸み */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* 薄い影 */
            padding: 2.5rem;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            margin: 2rem auto;  
        }

        /* フォームのタイトル */
        .form-container h2 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #e0e0e0;
        }

        /* フォームの説明文 */
        .form-container p {
            margin-top: 0;
            margin-bottom: 2rem;
            color: #e0e0e0;
            line-height: 1.6;
        }

        /* * -------------------
         * フォーム要素
         * -------------------
         */

        /* 各フォーム項目 (ラベル + 入力欄) のラッパー */
        .form-group {
            margin-bottom: 1.25rem; /* 各項目間の余白 */
        }

        /* ラベル (お名前、メールアドレスなど) */
        label {
            display: block;
            font-weight: 600; /* 画像に合わせた太字 */
            margin-bottom: 0.5rem; /* ラベルと入力欄の間の余白 */
            color: #e0e0e0;
        }

        /* 必須マーク (*) */
        .required {
            color: red;
            margin-left: 4px;
            font-weight: normal;
        }

        /* * -------------------
         * 入力欄 (input, textarea)
         * -------------------
         */
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%; /* 親要素いっぱいに広げる */
            padding: 12px; /* 内側の余白 */
            border: 1px solid #ccc; /* 画像に合わせた薄いグレーの枠線 */
            border-radius: 5px; /* 画像に合わせた角の丸み */
            box-sizing: border-box; /* paddingを含めてwidth: 100%にするため */
            font-size: 1rem;
            font-family: inherit; /* bodyのフォントを継承 */
        }

        /* プレースホルダー (入力ヒント) のスタイル */
        input::placeholder,
        textarea::placeholder {
            color: #aaa;
            opacity: 1;
        }

        /* テキストエリアの高さ設定 */
        textarea {
            min-height: 150px;
            resize: vertical; /* 縦方向のみリサイズ可能に */
        }

        /* * -------------------
         * 送信ボタン
         * -------------------
         */
        .submit-button {
            width: 100%;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background-color: #00bfff; /* 例: 青色のボタン */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #0097ca; /* ホバー時の色 */
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>お問い合わせ</h2>
        <p>
            ご質問、ご相談など、お気軽にお問い合わせください。
        </p>

        <form action="thanks.php" method="POST">
            <div class="form-group">
                <label for="name">お名前 <span class="required">*</span></label>
                <input type="text" id="name" name="name" placeholder="お名前を入力してください" required>
            </div>

            <div class="form-group">
                <label for="email">メールアドレス <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="メールアドレスを入力してください" required>
            </div>

            <div class="form-group">
                <label for="subject">件名</label>
                <input type="text" id="subject" name="subject" placeholder="件名を入力してください">
            </div>

            <div class="form-group">
                <label for="message">お問い合わせ内容 <span class="required">*</span></label>
                <textarea id="message" name="message" rows="6" placeholder="お問い合わせ内容を具体的に入力してください" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-button">送信する</button>
            </div>
        </form>
    </div>

</body>
</html>