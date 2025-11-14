<?php 
session_start(); 
require '../header.php'; 
require '../functions.php';



 debug($_REQUEST);


$pdo = getPDO();

if(isset($_SESSION['customer'])){
    $id = $_SESSION['customer']['user_id'];
    $sql = $pdo -> prepare('SELECT * FROM gg_users WHERE user_id != ? AND login_name = ?');
    $sql->execute([$id,$_REQUEST['login_name']]);
}else {
    $sql = $pdo -> prepare('SELECT * FROM gg_users WHERE login_name = ?');
    $sql->execute([$_REQUEST['login_name']]);
}

$plain_password = isset($_REQUEST['password']) ? h($_REQUEST['password']) : '';
$confirm_password = isset($_REQUEST['confirm_password']) ? h($_REQUEST['confirm_password']) : '';
$hashed_password = '';
$error='';
$min_length = 10;
$complexity_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{" . $min_length . ",}$/";
if($plain_password === $confirm_password){

    if (strlen($plain_password) < $min_length) {
        $error = "パスワードは少なくとも {$min_length} 文字の長さにする必要があります。";
    }elseif (!preg_match($complexity_regex, $plain_password)) {
        $error = "パスワードには、大文字、小文字、数字、特殊文字を組み合わせる必要があります。";
    }else {
        // Success: Passwords match and pass all validation checks!
        
        // 3. Hash the password securely for storage
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
        
        // Output for demonstration (in a real app, you'd redirect or process silently)
        echo "<h2>成功！パスワードをハッシュ化しました.</h2>";
        echo "<p>パスワードの確認に成功しました。このハッシュを保存する準備ができました。:</p>";
        echo "<pre>{$hashed_password}</pre>";
        echo "<p>検証成功: " . (password_verify($plain_password, $hashed_password) ? 'Yes' : 'No') . "</p>";

    } 
}else {
    // Failure: Passwords do not match
    $error = "パスワードと確認パスワードが一致しません.";
}

// Output any error message (or in a real app, pass it to the view)
if (!empty($error)) {
    echo "<h2>Error!</h2>";
    echo "<p>Error: <strong>" . $error . "</strong></p>";
}


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

$raw_postal_code = isset($_REQUEST['postalcode']) ? $_REQUEST['postalcode'] : '';
$clean_postal_code = preg_replace('/\D/', '', $raw_postal_code);
if (strlen($clean_postal_code) === 7) {
    $formatted_postal_code = substr($clean_postal_code, 0, 3) . '-' . substr($clean_postal_code, 3);
}


  $address = $_REQUEST['prefecture']." ".$_REQUEST['city']." ".$_REQUEST['address_line1'];
   $birthday = $_REQUEST['birth_year']."/".$_REQUEST['birth_month']."/".$_REQUEST['birth_day'];
if(empty($sql->fetchAll())){
    if(isset($_SESSION['customer'])){
       
        $sql = $pdo->prepare('UPDATE gg_users SET login_name=?, firstname = ?, firstname_kana=?, lastname = ? lastname_kana = ?, postal-code = ?, address = ?, phone_number = ?, mailaddress = ?, password = ?, gender = ?, birthday = ?,WHERE id =?');
        $sql->execute([
             h($_REQUEST['login_name']),
             h($_REQUEST['firstname ']),
             h($_REQUEST['firstname_kana']),
             h($_REQUEST['lastname']),
             h($_REQUEST['lastname_kana']),
             h($_REQUEST['lastname_kana']),
             h($_REQUEST['postal-code']),
             h($address),
             h($_REQUEST['phone_number']),
             h($_REQUEST['mailaddress']),
             h($_REQUEST['password']),
             h($_REQUEST['gender']),
             h($_REQUEST['birthday']),
            $id
        ]);

        $_SESSION['customer'] = [
            'use_id'         => $id,
            'login_name'     => $_REQUEST['login_name'],
            'firstname '     => $_REQUEST['firstname '],
            'firstname_kana' => $_REQUEST['firstname_kana'],
            'lastname'       => $_REQUEST['lastname'],
            'lastname_kana'  => $_REQUEST['lastname_kana'],
            'postal-code'    => $_REQUEST['postal-code'],
            'address'        => $_REQUEST['address'],
            'phone_number'   => $_REQUEST['phone_number'],
            'mailaddress'    => $_REQUEST['mailaddress'],
            'password'       => $_REQUEST['password'],
            'gender'         => $_REQUEST['gender'],
            'birthday'       => $_REQUEST['birthday'],
        ];

        echo 'お客様の情報変更しました。';
    }else{

    $postal_code = $_REQUEST['postalcode']; 
    
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
             h($_REQUEST['firstname_kana']),
             h($_REQUEST['lastname']),
             h($_REQUEST['lastname_kana']),
             h($clean_postal_code),
             h($address),
             h($phone_numbers),
             h($_REQUEST['mailaddress']),
             h($hashed_password),
             h($_REQUEST['gender']),
             h($birthday)
        ]);
        echo 'お客様の情報登録しました。';
    }
}else {
    echo "ログイン名がすでに利用されていますので、変更してください。";
}


 ?>