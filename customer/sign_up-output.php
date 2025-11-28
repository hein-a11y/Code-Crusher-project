<?php 
session_start(); 
require '../header.php'; 
require '../functions.php';



 debug($_REQUEST);


$pdo = getPDO();

if(isset($_SESSION['customer'])){
    $id = $_SESSION['customer']['user_id'];
    $sql = $pdo -> prepare('SELECT * FROM gg_users WHERE user_id != ? AND (login_name = ? OR mailaddress=?)');
    $sql->execute([$id,$_REQUEST['login_name'],$_REQUEST['mailaddress']]);
}else {
    $sql = $pdo -> prepare('SELECT * FROM gg_users WHERE login_name = ? OR mailaddress = ?');
    $sql->execute([$_REQUEST['login_name'],$_REQUEST['mailaddress']]);
}

$signUpInfo = $sql->fetch();

// (password)
$plain_password = isset($_REQUEST['password']) ? h($_REQUEST['password']) : '';
$confirm_password = isset($_REQUEST['password_confirm']) ? h($_REQUEST['password_confirm']) : '';

debug($plain_password);
debug($confirm_password);


$hashed_password = '';
$error_password='';
$min_length = 8;
$complexity_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{" . $min_length . ",}$/";
if($plain_password === $confirm_password){

    if (strlen($plain_password) < $min_length) {
        $error_password = "パスワードは少なくとも {$min_length} 文字の長さにする必要があります。";
    }
    
    if (!preg_match($complexity_regex, $plain_password)) {
        $error_password = "パスワードには、大文字、小文字、数字、特殊文字を組み合わせる必要があります。";
    }else {
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    }
}else {
    // Failure: Passwords do not match
    $error_password = "パスワードと確認パスワードが一致しません.";
}

// Output any error message (or in a real app, pass it to the view)
if (!empty($error)) {
    echo "<h2>Error!</h2>";
    echo "<p>Error: <strong>" . $error . "</strong></p>";
}






// (phone)
$phone_numbers = $_POST['phone_number']; // Assuming input from a form

if (ctype_digit($phone_numbers)) {
    // Input is valid (contains only digits)
    echo "電話番号は有効です: " ;
} else {
    // Input is invalid (contains non-digit characters, or is empty)
    $error_message = "Error: 電話番号には数字のみ含めることができます。";
    echo $error_message;
    // You would typically re-display the form with this error.
}




// (postal code)
$raw_postal_code = isset($_REQUEST['postalcode']) ? $_REQUEST['postalcode'] : '';
$clean_postal_code = preg_replace('/\D/', '', $raw_postal_code);
if (strlen($clean_postal_code) === 7) {
    $formatted_postal_code = substr($clean_postal_code, 0, 3) . '-' . substr($clean_postal_code, 3, 4);
}
echo "<p>郵便番号 : {$formatted_postal_code}</p>";
debug($formatted_postal_code);


// (katakana)
$firstname_kana = isset($_REQUEST['firstname_kana']) ? $_REQUEST['firstname_kana'] : '';
$lastname_kana = isset($_REQUEST['lastname_kana']) ? $_REQUEST['lastname_kana'] : '';
$katakana_regex = '/^[\p{Katakana}ー\s]+$/u';
if (!preg_match($katakana_regex, $firstname_kana) || !preg_match($katakana_regex, $lastname_kana)) {
    $kana_error = "フリガナ (firstname_kana および lastname_kana) には、全角**カタカナ**のみを使用してください。";
}




  $address = $_REQUEST['prefecture']." ".$_REQUEST['city']." ".$_REQUEST['address_line1'];
   $birthday = $_REQUEST['birth_year']."/".$_REQUEST['birth_month']."/".$_REQUEST['birth_day'];
if(empty($signUpInfo['login_name']) && empty($signUpInfo['mailaddress'])){
    // if(isset($_SESSION['customer'])){
       
    //     $sql = $pdo->prepare('UPDATE gg_users SET login_name=?, firstname = ?, firstname_kana=?, lastname = ? lastname_kana = ?, postalcode = ?, address = ?, phone_number = ?, mailaddress = ?, gender = ?, birthday = ? WHERE id =?');
    //     $sql->execute([
    //          h($_REQUEST['login_name']),
    //          h($_REQUEST['firstname']),
    //          h($_REQUEST['firstname_kana']),
    //          h($_REQUEST['lastname']),
    //          h($_REQUEST['lastname_kana']),
    //          h($_REQUEST['lastname_kana']),
    //          h($_REQUEST['postalcode']),
    //          h($address),
    //          h($_REQUEST['phone_number']),
    //          h($_REQUEST['mailaddress']),
    //          h($_REQUEST['gender']),
    //          h($_REQUEST['birthday']),
    //         $id
    //     ]);

    //     $_SESSION['customer'] = [
    //         'use_id'         => $id,
    //         'login_name'     => $_REQUEST['login_name'],
    //         'firstname '     => $_REQUEST['firstname '],
    //         'lastname'       => $_REQUEST['lastname'],
    //         'postalcode'    => $_REQUEST['postalcode'],
    //         'address'        => $_REQUEST['address'],
    //         'mailaddress'    => $_REQUEST['mailaddress'],
    //         'gender'         => $_REQUEST['gender'],
    //         'birthday'       => $_REQUEST['birthday'],
    //     ];

    //     echo 'お客様の情報変更しました。';
    // }else{

    // $postal_code = $_REQUEST['postalcode']; 
    
// // Regex: Matches 3 digits, followed by a hyphen, followed by 4 digits.
// $jp_regex = '/^\d{3}-\d{4}$/'; 

// if (preg_match($jp_regex, $postal_code)) {
//     echo "日本の郵便番号として有効です。";
// } else {
//     echo "日本の郵便番号として無効です。";
// }
       
       $sql = $pdo->prepare('INSERT INTO gg_users(user_id, login_name, firstname, firstname_kana, lastname, lastname_kana, postalcode, address, phone_number, mailaddress, password, gender, birthday)  VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?)');
        $sql->execute([
             h($_REQUEST['login_name']),
             h($_REQUEST['firstname']),
             h($firstname_kana),
             h($_REQUEST['lastname']),
             h($lastname_kana),
             h($formatted_postal_code),
             h($address),
             h($phone_numbers),
             h($_REQUEST['mailaddress']),
             h($hashed_password),
             h($_REQUEST['gender']),
             h($birthday)
        ]);
        echo 'お客様の情報登録しました。';
        echo '<a href="login-input.php">ログインする。</a>';
    // }
}else if(h($_REQUEST['login_name']) == $signUpInfo['login_name']) {
    echo "ログイン名がすでに利用されていますので、変更してください。";
}else{
    echo "メールアドレスがすでに利用されていますので、変更してください。";
}

debug($_SESSION['customer']);
 ?>