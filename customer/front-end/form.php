<?php require '../header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>

    <link rel="stylesheet" href="./css/form.css">
    
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