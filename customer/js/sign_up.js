// DOMが完全に読み込まれてからスクリプトを実行
document.addEventListener("DOMContentLoaded", function() {

    const yearSelect = document.getElementById("birth_year");
    const monthSelect = document.getElementById("birth_month");

    // --- 1. 生年月日のプルダウンを動的に生成 ---
    populateDateDropdowns();


    yearSelect.addEventListener('change', updateDayDropdown);
    monthSelect.addEventListener('change', updateDayDropdown);

    // --- 2. フォームの送信イベントを監視 ---
    const form = document.getElementById("registration-form");
    form.addEventListener("submit", function(event) {
        
        // デフォルトのフォーム送信（ページ遷移）を一旦停止
        event.preventDefault();

        // バリデーションを実行
        if (validateForm()) {
            // バリデーションが成功した場合
            alert("ご登録内容を送信します。\n(実際にはここで確認画面へ遷移します)");
            
            // 実際の送信処理
            form.submit();
        } else {
            // バリデーションが失敗した場合
            alert("入力内容にエラーがあります。\n必須項目をご確認ください。");
        }
    });

});

/**
 * フォームのバリデーションを実行する関数
 * @returns {boolean} バリデーションが成功したかどうか (true/false)
 */
function validateForm() {
    const form = document.getElementById("registration-form");
    let isValid = true;
    
    // 全てのエラー表示を一旦リセット
    resetErrorStyles(form);

    // --- A. 必須の入力欄 (text, email, password, select) をチェック ---
    const requiredFields = form.querySelectorAll("input[required], select[required]");
    
    requiredFields.forEach(function(field) {
        if (field.type === "checkbox") return; // チェックボックスは別で処理

        if (!field.value.trim()) {
            isValid = false;
            showError(field, "この項目は必須です。");
        }
    });

    // --- B. パスワードの一致をチェック ---
    const password = document.getElementById("password");
    const passwordConfirm = document.getElementById("password_confirm");
    
    if (password.value && passwordConfirm.value && password.value !== passwordConfirm.value) {
        isValid = false;
        showError(password, "パスワードが一致しません。");
        showError(passwordConfirm, "パスワードが一致しません。");
    }
    const firstname_kana = document.getElementById("firstname_kana");
    
    const katakanaRegex = /^[\u30A0-\u30FF]+$/;
    if (!katakanaRegex.test(firstname_kana.value)){
        isValid = false;
        showError(firstname_kana,"フリガナ (firstname_kana) には、全角**カタカナ**のみを使用してください。");
    }

    const lastname_kana = document.getElementById("lastname_kana");
    if (!katakanaRegex.test(lastname_kana.value)){
        isValid = false;
        showError(lastname_kana,"フリガナ (lastname_kana) には、全角**カタカナ**のみを使用してください。");
    }
    
    // --- C. 利用規約の同意チェックボックス ---
    const termsAgree = document.getElementById("terms_agree");
    if (!termsAgree.checked) {
        isValid = false;
        showError(termsAgree, "規約への同意が必要です。");
    }

    return isValid;
}

/**
 * エラー表示を適用する関数
 * @param {HTMLElement} field - エラー対象の入力要素
 * @param {string} message - エラーメッセージ (現在は未使用だが拡張用)
 */
function showError(field, message) {
    
    if (field.type === "checkbox") {
        // チェックボックスは親ラベルの色を変える
        const label = field.closest("label");
        if(label) {
            label.classList.add("input-error");
            
        }
    } else {
        // 通常の入力欄は枠線を赤くする
        field.classList.add("input-error");
        
    }
}

/**
 * フォーム内のすべてのエラースタイルをリセットする関数
 * @param {HTMLFormElement} form - 対象のフォーム
 */
function resetErrorStyles(form) {
    // 赤枠をリセット
    form.querySelectorAll(".input-error").forEach(function(el) {
        el.classList.remove("input-error");
    });
    
    // (ここに、エラーメッセージ自体をクリアする処理も追加できる)
}

/**
 * 生年月日のプルダウンメニューを動的に生成する関数
 */
function populateDateDropdowns() {
    const yearSelect = document.getElementById("birth_year");
    const monthSelect = document.getElementById("birth_month");
    // const daySelect = document.getElementById("birth_day");
    // let monthValue = document.querySelector('birth_month');

    const currentYear = new Date().getFullYear();
    
    // 年 (18歳以上を想定し、過去100年分)
    for (let i = currentYear - 18; i >= currentYear - 100; i--) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i + "年";
        yearSelect.appendChild(option);
    }

    // 月 (1-12)
    for (let i = 1; i <= 12; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i + "月";
        monthSelect.appendChild(option);
    }


    // yearSelect.addEventListener('change', yearSelect.value);
    // monthSelect.addEventListener('change', monthSelect.value);
}

const updateDayDropdown = (() => {
    const yearSelect = document.getElementById("birth_year");
    const monthSelect = document.getElementById("birth_month");
    const daySelect = document.getElementById("birth_day");

    const selectedYear = parseInt(yearSelect.value);
    const selectedMonth = parseInt(monthSelect.value);

    if (isNaN(selectedYear) || isNaN(selectedMonth)) {
        return;
    }


    // 日 (1-31) 
    // ※ 月によって日数を変える（うるう年対応）のは複雑になるため、
    //   ここでは簡易的に31日まで生成します。
    // if(yearSelect.value !== '' &&  monthSelect.value !== ''){
    const last_day = new Date(yearSelect.value,monthSelect.value,0).getDate();
    daySelect.textContent = '';
    for (let i = 1; i <= last_day; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.textContent = i + "日";
        daySelect.appendChild(option);
    }
});

