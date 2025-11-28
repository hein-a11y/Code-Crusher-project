<?php require_once '../header.php'; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/sign_up.css">
    
    <title>新規会員登録</title>
</head>
<body>

    <div class="registration-container">

        <main class="main-content">

            <section class="form-section">
                <h2>新規会員登録</h2>
                
                <div class="form-notes">
                    <ul>
                        <li>「<span class="required-badge">必須</span>」と表示されている項目は、必ずご入力ください。</li>
                        <li>ご登録いただいた個人情報は、SSLで暗号化され安全に送信されます。</li>
                    </ul>
                </div>

                <form id="registration-form" action="signup_output.php" method="post" novalidate>
                    
                    <fieldset class="form-fieldset">
                        <legend class="fieldset-legend">会員情報</legend>

                        <div class="form-group">
                            <label for="login_name">名前 <span class="required-badge">必須</span></label>
                            <input type="text" id="login_name" name="login_name" value="山田太郎">
                        </div>

                        <div class="form-group">
                            <label for="firstname">苗字 <span class="required-badge">必須</span></label>
                            <input type="text" id="firstname" name="firstname" required placeholder="姓 (例) 山田" value="山田">
                        </div>

                        <div class="form-group">
                            <label for="firstname_kana">苗字(フリガナ)<span class="required-badge">必須</span></label>
                            <div class="form-group-inline">
                                <input type="text" id="firstname_kana" name="firstname_kana" required placeholder="セイ (例) ヤマダ"  value="ヤマダ"><br>
                                
                            </div>
                              <small>カタカナのみで入力する必要があります</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastname">名 <span class="required-badge">必須</span></label>
                            <div class="form-group-inline">
                                <input type="text" id="lastname" name="lastname" required placeholder="名 (例) 太郎" value="太郎">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname_kana">名 <span class="required-badge">必須</span></label>
                            <div class="form-group-inline">
                                <input type="text" id="lastname_kana" name="lastname_kana" required placeholder="メイ (例) タロウ" value="タロウ"><br>
                            </div>
                             <small>カタカナのみで入力する必要があります</small>
                        </div>

                        <div class="form-group">
                            <label for="postal-code">郵便番号</label>
                            <input type="text" id="postal-code" name="postalcode" class="postal-code" placeholder="例: 100-0001" maxlength="7" value="5450042">
                            <div id="error-message" class="message"></div>
                            <small>ハイフンなしで入力してください</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="prefecture">都道府県 <span class="required-badge">必須</span></label>
                                <input type="text" id="prefecture" name="prefecture" required placeholder="例) 〇〇県" value="大阪府">
                        </div>

                        <div class="form-group">
                            <label for="city">市区町村 <span class="required-badge">必須</span></label>
                            <input type="text" id="city" name="city" required placeholder="例) 〇〇区九条北" value="大阪市阿倍野区丸山通">
                        </div>
                        
                        <div class="form-group">
                            <label for="address_line1">番地 <span class="required-badge">必須</span></label>
                            <input type="text" id="address_line1" name="address_line1" required placeholder="例) 1-13-12" value="1-6-3">
                        </div>

                        <div class="form-group">
                            <label for="phone_number">電話番号 <span class="required-badge">必須</span></label>
                            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]*" required placeholder="例) 02012345678" maxlength="11" value="02012345678">
                        </div>

                        <div class="form-group">
                            <label for="mailaddress">メール<span class="required-badge">必須</span></label>
                            <input type="email" id="mailaddress" name="mailaddress" required placeholder="例)example@gmail.com " value="example@gmail.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">パスワード <span class="required-badge">必須</span></label>
                            <input type="password" id="password" name="password" required>
                            <small>パスワードには、大文字、小文字、数字、特殊文字を組み合わせる必要があります。</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">パスワード (確認入力) <span class="required-badge">必須</span></label>
                            <input type="password" id="password_confirm" name="password_confirm" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="birthday">生年月日 <span class="required-badge">必須</span></label>
                            <div class="form-group-inline date-select">
                                <select id="birth_year" name="birth_year" required>
                                    <option value="">年</option>
                                    </select>
                                <select id="birth_month" name="birth_month" required>
                                    <option value="">月</option>
                                    </select>
                                <select id="birth_day" name="birth_day" required>
                                    <option value="">日</option>
                                    </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>性別 <span class="required-badge">必須</span></label>
                            <div class="form-group-radio">
                                <label><input type="radio" name="gender" value="unregister" checked> ご登録なし</label>
                                <label><input type="radio" name="gender" value="female"> 女性</label>
                                <label><input type="radio" name="gender" value="male"> 男性</label>
                            </div>
                        </div>
                    </fieldset>

                    

                    <fieldset class="form-fieldset">
                        <legend class="fieldset-legend">メールマガジンの配信設定</legend>
                        <div class="form-group-checkbox">
                            <label>
                                <input type="checkbox" name="newsletter_uniqlo" value="1">
                                ユニクロメールマガジン
                            </label>
                        </div>
                        <div class="form-group-checkbox">
                            <label>
                                <input type="checkbox" name="newsletter_gu" value="1" checked>
                                ジーユーメールマガジン (HTML形式)
                            </label>
                        </div>
                    </fieldset>
                    
                    <fieldset class="form-fieldset">
                        <legend class="fieldset-legend">ご利用規約</legend>
                        <div class="terms-box">
                            <p>第1条（本規約の適用）</p>
                            <p>株式会社ジーユー（以下「当社」といいます）は、このジーユーオンラインストア利用規約（以下「本規約」といいます）に基づき、お客様にジーユーオンラインストアサービス（以下「本サービス」といいます）を提供します。</p>
                            <p>...</p>
                            <p>（ここに長い利用規約が続きます）</p>
                            <p>...</p>
                            <p>お客様は、本サービスを利用するにあたり、本規約に同意するものとします。</p>
                        </div>
                        <div class="form-group-checkbox agreement">
                            <label for="terms_agree">
                                <input type="checkbox" id="terms_agree" name="terms_agree" value="1" required>
                                「ご利用規約」に同意する <span class="required-badge">必須</span>
                            </label>
                        </div>
                    </fieldset>

                    <div class="form-submit-button">
                        <button type="submit">確認画面へ進む</button>
                    </div>
                </form>

            </section>

            <aside class="sidebar-section">
                <div class="sidebar-widget">
                    <h3>よくあるご質問</h3>
                    <ul>
                        <li><a href="#">新規会員登録の方</a></li>
                        <li><a href="#">お支払いについて</a></li>
                        <li><a href="#">配送について</a></li>
                        <li><a href="#">返品・交換について</a></li>
                        <li><a href="#">オンラインストアのご利用のまとめ</a></li>
                        <li><a href="#">プライバシーポリシー</a></li>
                    </ul>
                </div>
                <div class="sidebar-widget">
                    <h3>ご利用ガイド</h3>
                    <ul>
                        <li><a href="#">店舗レジ支払いについて</a></li>
                        <li><a href="#">ギフトサービスについて</a></li>
                        <li><a href="#">ご注文のキャンセルについて</a></li>
                        <li><a href="#">お客様窓口はこちら</a></li>
                    </ul>
                </div>
            </aside>
        </main>

        <footer class="footer">
            <nav class="footer-nav">
                <ul>
                    <li><a href="#">企業情報</a></li>
                    <li><a href="#">採用情報</a></li>
                    <li><a href="#">利用規約</a></li>
                    <li><a href="#">プライバシーポリシー</a></li>
                </ul>
            </nav>
            <p class="copyright">&copy; G.U. CO., LTD. ALL RIGHTS RESERVED.</p>
        </footer>

    </div>
    <script src="./js/sign_up_address.js" defer></script>
    <script src="./js/sign_up.js" defer></script>
</body>
</html>