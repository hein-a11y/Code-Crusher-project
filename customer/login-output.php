
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

    $login_password = $_REQUEST['password'];

    unset($_SESSION['customer']);
    $pdo = getPDO();
    if($login_type == "email"){
        $sql = "select * from gg_users where  mailaddress=? limit 1";
    }else{
        $sql = "SELECT * FROM gg_users WHERE login_name = ? limit 1";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([h($login)]);

    foreach($stmt as $row){
        $_SESSION['customer']=[
            'user_id'        => $row['user_id'],
            'firstname'      => $row['firstname'],
            'lastname'       => $row['lastname'],
            'address'        => $row['address'],
            'login'          => $row["login_name"]
        ];
        $stored_hash = $row['password'];
    }

    if(isset($_SESSION['customer']) && password_verify($login_password, $stored_hash)){
        unset($_SESSION['error_message']);
        header("location: index.php");
        exit;

    }else {
        unset($_SESSION['customer']);
        $_SESSION['error_message'] = "ログイン名またはパスワードが違います。";
        header("location: login-input.php");
        exit;
    }


?>

              <!-- taro.tanaka@example.com -->
            <!-- $2y$10$examplehashedpassword1 -->