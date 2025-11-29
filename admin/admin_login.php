<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG ADMIN - ログイン</title>
    
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="./css/admin_login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>

    <div class="card login-card">
        <h1 class="login-header">GG ADMIN</h1>

        <form action="admin_dashboard.php" method="POST">
            
            <div class="form-group">
                <label for="login_name" class="form-label">ログイン名</label>
                <input type="text" id="login_name" name="login_name" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <button type="submit" class="button button-primary button-full-width">
                ログイン
            </button>

            <div class="link-footer">
                <a href="admin_register.php">新規登録はこちら</a>
            </div>

        </form>
    </div>
</body>
</html>