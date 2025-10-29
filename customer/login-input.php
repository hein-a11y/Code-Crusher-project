<?php require_once '../header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - My Game & Gadget Store</title>
    
    <link rel="stylesheet" href="./css/login.css">

</head>
<body>
    <main>
        <div class="login-container">
            <form id="login-form">
                <h1>ログイン</h1>

                <div class="input-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" placeholder="email@example.com" required>
                </div>

                <div class="input-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" placeholder="パスワード" required>
                </div>

                <div class="options-group">
                <label>
                    <input type="checkbox" name="remember"> ログイン状態を保持
                </label>
                <a href="#" class="forgot-password">パスワードをお忘れですか？</a>
                </div>

                <button type="submit" class="login-button">ログイン</button>

                <div class="signup-link">
                <p>アカウントをお持ちでない場合 <a href="./sign_up-input.php">新規登録</a></p>
                </div>
            </form>
        </div>
    </main>
    
    <script src="script.js"></script>
    
</body>
</html>