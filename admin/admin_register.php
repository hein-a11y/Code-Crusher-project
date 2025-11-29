<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG ADMIN - 新規登録</title>
    
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/admin_register.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="card login-card">
        <h1 class="login-header">GG ADMIN - 新規登録</h1>

        <form action="admin_login.php" method="POST">

            <div class="form-group">
                <label for="login_name" class="form-label">ログイン名</label>
                <input type="text" id="login_name" name="login_name" class="form-input" required>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="lastname" class="form-label">姓</label>
                    <input type="text" id="lastname" name="lastname" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="firstname" class="form-label">名</label>
                    <input type="text" id="firstname" name="firstname" class="form-input" required>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="lastname_kana" class="form-label">姓 (カナ)</label>
                    <input type="text" id="lastname_kana" name="lastname_kana" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="firstname_kana" class="form-label">名 (カナ)</label>
                    <input type="text" id="firstname_kana" name="firstname_kana" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label for="mailaddress" class="form-label">メールアドレス</label>
                <input type="email" id="mailaddress" name="mailaddress" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="form-label">パスワード (確認用)</label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-input" required>
            </div>
            
            <button type="submit" class="button button-primary button-full-width">
                登録する
            </button>

            <div class="link-footer">
                <a href="admin_login.php">ログインはこちら</a>
            </div>

        </form>
    </div>

</body>
</html>