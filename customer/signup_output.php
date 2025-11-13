<?php 
session_start(); 
require '../header.php'; 
require '../functions.php';




 


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
$hashed_password = !empty($plain_password) ? password_hash($plain_password, PASSWORD_DEFAULT) : '';

if(empty($sql->fetchAll())){
    if(isset($_SESSION['customer'])){
         $address = $_REQUEST['prefecture'].$_REQUEST['city'].$_REQUEST['address_line1'];
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
       
       $sql = $pdo->prepare('INSERT INTO gg_users VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?)');
        $sql->execute([
             h( $_REQUEST['login_name']),
             h($_REQUEST['firstname']),
             h($_REQUEST['firstname_kana']),
             h($_REQUEST['lastname']),
             h($_REQUEST['lastname_kana']),
             h($postal_code),
             h($address),
             h($_REQUEST['phone_number']),
             h($_REQUEST['mailaddress']),
             h($hashed_password),
             h($_REQUEST['gender']),
             h($_REQUEST['birthday'])
        ]);
        echo 'お客様の情報登録しました。';
    }
}else {
    echo "ログイン名がすでに利用されていますので、変更してください。";
}

 require '../footer.php'
 ?>