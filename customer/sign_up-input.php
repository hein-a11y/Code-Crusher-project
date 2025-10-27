<?php require '../header-input.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/sign_up.css">
    <script src="./js/sign_up_address.js" defer></script>
    <script src="./js/sign_up.js" defer></script>
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

                <form id="registration-form" action="#" method="post" novalidate>
                    
                    <fieldset class="form-fieldset">
                        <legend class="fieldset-legend">会員情報</legend>

                        <div class="form-group">
                            <label for="email">メールアドレス <span class="required-badge">必須</span></label>
                            <input type="email" id="email" name="email" required placeholder="例) tarougu@example.com">
                        </div>

                        <div class="form-group">
                            <label>お名前 <span class="required-badge">必須</span></label>
                            <div class="form-group-inline">
                                <input type="text" id="last_name" name="last_name" required placeholder="姓 (例) 山田">
                                <input type="text" id="first_name" name="first_name" required placeholder="名 (例) 太郎">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>フリガナ <span class="required-badge">必須</span></label>
                            <div class="form-group-inline">
                                <input type="text" id="last_name_kana" name="last_name_kana" required placeholder="セイ (例) ヤマダ">
                                <input type="text" id="first_name_kana" name="first_name_kana" required placeholder="メイ (例) タロウ">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="postal-code">郵便番号</label>
                            <input type="text" id="postal-code" class="postal-code" placeholder="例: 1000001" maxlength="7">
                            <div id="error-message" class="message"></div>
                            <small>ハイフンなしで入力してください</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="prefecture">都道府県 <span class="required-badge">必須</span></label>
                                <input type="text" id="prefecture" name="prefecture" required placeholder="例) 〇〇県">
                        </div>

                        <div class="form-group">
                            <label for="city">市区町村 <span class="required-badge">必須</span></label>
                            <input type="text" id="city" name="city" required placeholder="例) 〇〇区九条北">
                        </div>
                        
                        <div class="form-group">
                            <label for="address_line1">番地 <span class="required-badge">必須</span></label>
                            <input type="text" id="address_line1" name="address_line1" required placeholder="例) 1-13-12">
                        </div>

                        <div class="form-group">
                            <label for="address_line2">アパート・マンション名</label>
                            <input type="text" id="address_line2" name="address_line2" placeholder="例) ジーユービル 101号室">
                        </div>

                        <div class="form-group">
                            <label for="phone">電話番号 <span class="required-badge">必須</span></label>
                            <input type="tel" id="phone" name="phone" required placeholder="例) 0312345678">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">パスワード <span class="required-badge">必須</span></label>
                            <input type="password" id="password" name="password" required>
                            <small>半角英数記号8文字以上で入力してください</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">パスワード (確認入力) <span class="required-badge">必須</span></label>
                            <input type="password" id="password_confirm" name="password_confirm" required>
                        </div>
                        
                        <div class="form-group">
                            <label>生年月日 <span class="required-badge">必須</span></label>
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
                        <legend class="fieldset-legend">支払い方法</legend>

                        <div class="form-group">
                            <label for="card_number">カード番号 <span class="required-badge">必須</span></label>
                            <input type="text" id="card_number" name="card_number" required placeholder="半角数字のみ" pattern="[0-9]*" inputmode="numeric">
                        </div>

                        <div class="form-group">
                            <label for="card_company">カード会社 <span class="required-badge">必須</span></label>
                            <select id="card_company" name="card_company" required>
                                <option value="visa">VISA</option>
                                <option value="mastercard">MasterCard</option>
                                <option value="jcb">JCB</option>
                                <option value="amex">American Express</option>
                                <option value="diners">Diners Club</option>
                                <option value="discover">Discover</option>
                            </select>
                            <div class="payment-card-logos">
                                <span class="card-logo visa">VISA</span>
                                <span class="card-logo mastercard">Mastercard</span>
                                <span class="card-logo saison">SAISON</span>
                                <span class="card-logo jcb">JCB</span>
                                <span class="card-logo amex">AMEX</span>
                                <span class="card-logo diners">Diners</span>
                                <span class="card-logo discover">Discover</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>有効期限 <span class="required-badge">必須</span></label>
                            <div class="form-group-inline expiry-date-select">
                                <select id="expiry_month" name="expiry_month" required>
                                    <option value="">月</option>
                                    </select>
                                <span>月</span>
                                <select id="expiry_year" name="expiry_year" required>
                                    <option value="">年</option>
                                    </select>
                                <span>年</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="security_code">セキュリティコード <span class="required-badge">必須</span></label>
                            <input type="text" id="security_code" name="security_code" required placeholder="カード背面4桁もしくは3桁の番号" pattern="[0-9]*" inputmode="numeric" maxlength="4">
                            <div class="security-code-help">
                                <a href="#" target="_blank">
                                    <span class="help-icon">?</span>
                                    カード裏面の番号とは？
                                </a>
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

</body>
</html>