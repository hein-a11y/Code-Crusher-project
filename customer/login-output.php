
<?php require "../functions.php"; ?>
<?php
session_start();

// include "connect.php";
    $login = trim($_REQUEST['login']);
    $login_type = "";
    if(filter_var($login, FILTER_VALIDATE_EMAIL)){
        $login_type = "email";
    }else{
        $login_type = "login_name";
    }

    unset($_SESSION['customer']);
    $pdo = getPDO();
    if($login_type == "email"){
        $sql = "select * from gg_users where  mailaddress=? and password=?";
    }else{
        $sql = "SELECT FROM gg_users WHERE login_name = ? AND password = ?";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([h($login),h($_REQUEST['password'])]);

    foreach($stmt as $row){
        $_SESSION['customer']=[
            'user_id'        => $row['user_id'],
            'firstname'      => $row['firstname'],
            'lastname'       => $row['lastname'],
            'address'        => $row['address'],
            'login'          => $row["login_name"]
        ];
    }

    if(isset($_SESSION['customer'])){
        unset($_SESSION['error_message']);
        header("location: index.php");
        exit;

    }else {
        $_SESSION['error_message'] = 'ログイン名またはパスワードが違います。'
    }


?>

              <!-- taro.tanaka@example.com -->
            <!-- $2y$10$examplehashedpassword1 -->