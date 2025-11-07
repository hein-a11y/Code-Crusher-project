<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - My Game & Gadget Store</title>
    <style>
        /* --- 
         * 基本設定と背景
         * --- */
         
        :root {
            --primary-color: #00bfff; /* Neon blue accent */
            --background-color: #121212; /* Deep black */
            --surface-color: #1e1e1e;   /* Slightly lighter for cards/headers */
            --text-color: #e0e0e0;      /* Light grey for readability */
            --text-secondary-color: #a0a0a0; /* Dimmer text */
        }

        main {
            /* ユーザーリクエスト: 全体的な色は黒 */
            background-color: #333; /* 真っ黒より少し柔らかいダークグレー */
            color: #ffffff; /* 白文字 */
            
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Hiragino Kaku Gothic ProN", "Meiryo", sans-serif;
            margin: 0;
            
            /* フォームを画面の中央に配置する */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .line{
             text-decoration: none;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            text-shadow: 0 0 5px var(--primary-color);
            
        }

        
        header {
            background-color: var(--surface-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }


        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 1.5rem;
        }

        /*main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }*/

        /* --- 
         * ログインフォームのコンテナ
         * --- */
        .login-container {
            background-color: #1e1e1e; /* 背景より少し明るい黒 */
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box; /* paddingを含めて幅を計算 */
        }

        /* --- 
         * フォーム内の要素
         * --- */
        #login-form h1 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        /* 各入力グループ (ラベル + 入力欄) */
        .input-group {
            margin-bottom: 1.25rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #b0b0b0; /* ラベルの色を少し抑える */
        }

        /* --- 
         * 入力欄のスタイル
         * --- */
        .input-group input[type="email"],
        .input-group input[type="password"] {
            /* ユーザーリクエスト: ログインの記入欄は白 */
            background-color: #ffffff;
            color: #121212; /* 白い背景なので、文字は黒に */
            
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box; /* paddingを含めて幅を計算 */
            
            /* 入力中の見た目 */
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        /* 入力欄がフォーカスされた時のスタイル */
        .input-group input:focus {
            outline: none;
            border-color: #007bff; /* フォーカス時に青い枠線 */
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        /* --- 
         * オプション (ログイン保持 / パスワード忘れ)
         * --- */
        .options-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #b0b0b0;
            margin-bottom: 1.5rem;
        }
        
        .options-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .options-group input[type="checkbox"] {
            margin-right: 0.5em;
        }
        
        .options-group a {
            color: #007bff; /* リンクの色 */
            text-decoration: none;
        }

        .options-group a:hover {
            text-decoration: underline;
        }

        /* --- 
         * ログインボタン
         * --- */
        .login-button {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 5px;
            
            /* サイトのテーマカラー（例: 明るい青） */
            background-color: #007bff; 
            color: white;
            
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #0056b3; /* ホバー時 */
        }

        /* --- 
         * 新規登録リンク
         * --- */
        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #b0b0b0;
        }
        
        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
        
    </style>
</head>
<body>

    <header>
        <div class="logo">GG STORE</div>
        
        <div class="header-actions">
           
    </header>
    
    <main>
        <div class="login-container">
            <form id="login-form" action="login_output.php"  method="post"> 
                <h1>ログイン</h1>


                <div class="input-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" placeholder="email@example.com" required>
                </div>

                <div class="input-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" placeholder="パスワード" required>
                </div>

                <input type="submit" class="btn " value="Sign up" name="Signup">

                <div class="options-group">
                <label>
                    <input type="checkbox" name="remember"> ログイン状態を保持
                </label>
                <a href="#" class="forgot-password">パスワードをお忘れですか？</a>
                </div>

                <button type="submit" class="login-button">ログイン</button>

                <div class="signup-link">
                <p>アカウントをお持ちでない場合 <a href="signIn_input.php">Sign in</a></p>
                </div>
            </form>
        </div>
    </main>
</body>
</html>