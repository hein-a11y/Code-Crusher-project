<?php
    session_start();

    $error_message = "";
    $is_error = false;
    if(isset($_SESSION['error_message'])){
        $error_message = htmlspecialchars($_SESSION['error_message']);
        $is_error = true;
        unset($_SESSION['error_message']);
    }
?>

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
            <?php 
                if($is_error){
                    echo <<< HTML
                        <div class="error-popup">
                         {$error_message};
                        </div> 
                    HTML;   
                }
            ?>
            <form id="login-form" action="login-output.php" method="post">
                <h1>ログイン</h1>

                <div class="input-group">
                <label for="login">メールアドレスかログイン名</label>
                <input type="text" id="login" name="login" placeholder="email@example.com" required>
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