<?php require '../header/header-1.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>よくあるご質問 (FAQ)</title>
    <!-- フォント（Noto Sans JP）を読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    <style>


        /* --- コンテナ --- */
        .container {
            max-width: 800px; /* 最大幅 */
            margin: 40px auto; /* 上下マージンと左右中央揃え */
            padding: 0 20px; /* モバイル用の左右パディング */
        }

        /* --- ヘッダー（タイトル） --- */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header .sub-title {
            font-size: 2rem;
            font-weight: 700;
            color: #e0e0e0; /* 画像の薄い「FAQ」 */
            letter-spacing: 2px;
        }

        .header .main-title {
            font-size: 2rem; /* 32px */
            font-weight: 700;
            color: #e0e0e0; /* メインタイトルの色 */
            margin-top: 8px;
        }

        /* --- FAQリストコンテナ --- */
        #faq-container {
            width: 100%;
        }

        /* --- 各FAQアイテム --- */
        .faq-item {
            background-color: #333;
            border-radius: 12px; /* 角丸 */
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04); /* 影 */
            overflow: hidden; /* 角丸を維持するため */
            transition: box-shadow 0.3s ease;
        }
        .faq-item:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.07);
        }

        /* --- 質問部分 --- */
        .faq-question {
            display: flex;
            align-items: center;
            padding: 20px;
            cursor: pointer;
            user-select: none; /* テキスト選択を無効化 */
        }

        /* --- Q/A アイコン共通スタイル --- */
        .faq-icon {
            width: 28px;
            height: 28px;
            min-width: 28px; /* 縮まないように */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            color: #ffffff;
            margin-right: 15px;
        }
        
        /* Q アイコン（画像参照） */
        .q-icon {
            background-color: #00bfff; /* 青系 */
        }
        
        /* A アイコン（画像参照） */
        .a-icon {
            background-color: #00bfff; /* グレー系 */
        }

        /* 質問テキスト */
        .faq-question-text {
            flex-grow: 1; /* 残りのスペースを埋める */
            font-weight: 700;
            color: #e0e0e0;
        }

        /* 開閉トグル (+/-) */
        .faq-toggle {
            font-size: 24px;
            font-weight: 700;
            color: #00bfff; /* 青系 */
            margin-left: 15px;
            transition: transform 0.2s ease, color 0.2s ease;
        }
        
        /* active（開いた）状態のトグル */
        .faq-item.active .faq-toggle {
            color: #00bfff; /* グレー系（または赤系 #d0021b） */
            transform: rotate(180deg); /* アニメーション（+を-に見せるため）*/
        }

        /* --- 回答部分 --- */
        .faq-answer {
            /* 初期状態は非表示 */
            display: none;
            padding: 0 20px 20px 20px; /* 上パディングはボーダーで制御 */
        }
        
        /* active（開いた）状態の回答 */
        .faq-item.active .faq-answer {
            display: flex; /* Flexboxでアイコンとテキストを横並び */
            align-items: flex-start; /* アイコンとテキストの上端を揃える */
            
            border-top: 1px dashed #e0e0e0; /* 質問と回答の間の区切り線 */
            margin: 0 20px; /* 左右のパディング分をマージンで相殺 */
            padding: 20px 0 20px 0; /* ボーダーの上(20px)と下(20px)にパディングを追加（変更点） */
        }

        /* 回答テキスト */
        .faq-answer-text {
            flex-grow: 1;
            color: #e0e0e0;
        }

        /* --- お問い合わせセクション --- */
        .contact-section {
            background-color: #121212;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            text-align: center;
            padding: 30px 20px;
            margin-top: 40px;
        }

        .contact-title {
            font-size: 1.5rem; /* 24px */
            font-weight: 700;
            color: #e0e0e0;
            margin-bottom: 12px;
        }

        .contact-description {
            font-size: 1rem; /* 16px */
            color: #e0e0e0;
            margin-bottom: 24px;
        }

        .contact-button {
            background-color: #00bfff; /* Qアイコンと同じ青系 */
            color: #e0e0e0;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact-button:hover {
            background-color: #0097ca; /* 少し濃い青 */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- ヘッダー -->
        <div class="header">
            <div class="sub-title">FAQ</div>
            <h1 class="main-title">よくあるご質問</h1>
        </div>

        <!-- FAQリストがここに動的に挿入されます -->
        <main id="faq-container">
            <!-- 
            <div class="faq-item">
                <div class="faq-question">
                    <span class="faq-icon q-icon">?</span>
                    <span class="faq-question-text">質問サンプル</span>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <span class="faq-icon a-icon">A</span>
                    <p class="faq-answer-text">回答サンプルテキスト。</p>
                </div>
            </div> 
            -->
        </main>

    </div>

    <script>
        // --- FAQデータ ---
        // ここに質問と回答のオブジェクトを追加するだけで、
        // ページ読み込み時に自動的にFAQリストが生成されます。
        const faqData = [
            {
                question: "キャリレコの利用は無料ですか？",
                answer: "はい、登録やログインは不要で無料でご利用いただけます。"
            },
            {
                question: "アカウント登録やログインは必要ですか？",
                answer: "登録やログインは不要でご利用いただけます。",
            },
            {
                question: "入力データは保存されますか？",
                answer: "いいえ、入力されたデータは保存されません。ページを離れるとリセットされますのでご注意ください。"
            },
            {
                question: "コンビニ印刷はどのコンビニで利用できますか？",
                answer: "全国の主要なコンビニエンスストア（セブン-イレブン、ファミリーマート、ローソンなど）のマルチコピー機に対応しています。"
            },
            {
                question: "ほかの履歴書作成サービスとの違いはなんですか？",
                answer: "当サービスは「登録不要」「無料」「シンプル」を特徴としており、必要なときにすぐに履歴書を作成できる手軽さを追求しています。"
            }
        ];

        // --- JavaScript実行 ---
        // DOM（HTML）の読み込みが完了したら実行
        document.addEventListener('DOMContentLoaded', function() {
            
            const container = document.getElementById('faq-container');
            if (!container) return; // コンテナが見つからない場合は終了

            // 1. FAQデータを元にHTMLを生成
            faqData.forEach(itemData => {
                // faq-item (全体) を作成
                const faqItem = document.createElement('div');
                faqItem.className = 'faq-item';

                // 質問部分のHTML
                const questionHTML = `
                    <div class="faq-question">
                        <span class="faq-icon q-icon">?</span>
                        <span class="faq-question-text">${itemData.question}</span>
                        <span class="faq-toggle">${itemData.active ? '−' : '+'}</span>
                    </div>
                `;

                // 回答部分のHTML
                const answerHTML = `
                    <div class="faq-answer">
                        <span class="faq-icon a-icon">A</span>
                        <p class="faq-answer-text">${itemData.answer}</p>
                    </div>
                `;

                // 作成したHTMLをfaq-itemに挿入
                faqItem.innerHTML = questionHTML + answerHTML;
                
                // もしデータに active: true が設定されていれば、初期状態で開く
                if (itemData.active) {
                    faqItem.classList.add('active');
                }

                // コンテナにfaq-itemを追加
                container.appendChild(faqItem);
            });

            // 2. クリックイベント（アコーディオン機能）を設定
            // コンテナ（親）にイベントリスナーを1つだけ設定（イベント委任）
            container.addEventListener('click', function(event) {
                // クリックされた要素が .faq-question またはその子要素か確認
                const questionHeader = event.target.closest('.faq-question');
                
                // .faq-question 以外がクリックされた場合は何もしない
                if (!questionHeader) return;

                // クリックされた質問の親（.faq-item）を取得
                const faqItem = questionHeader.parentElement;
                
                // トグル（+/-）要素を取得
                const toggle = questionHeader.querySelector('.faq-toggle');

                // activeクラスを付け外し
                faqItem.classList.toggle('active');

                // クラスの状態に応じてトグルのテキストを変更
                if (faqItem.classList.contains('active')) {
                    toggle.textContent = '−'; // マイナス記号
                } else {
                    toggle.textContent = '+';
                }

                // (オプション) クリックしたもの以外をすべて閉じる場合
                // container.querySelectorAll('.faq-item').forEach(item => {
                //     if (item !== faqItem) {
                //         item.classList.remove('active');
                //         item.querySelector('.faq-toggle').textContent = '+';
                //     }
                // });
            });
        });

    </script>

    <!-- お問い合わせセクション -->
    <section class="contact-section">
        <h2 class="contact-title">問題が解決しませんか？</h2>
        <p class="contact-description">サポートチームまでお気軽にお問い合わせください。</p>
        <!-- 実際のリンク先に合わせて href を変更してください -->
        <a href="form.php" class="contact-button" role="button">お問い合わせフォームへ</a>
    </section>
</body>
</html>
