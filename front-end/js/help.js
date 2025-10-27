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