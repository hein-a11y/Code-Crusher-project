
<?php require "../functions.php"; ?>
<?php
session_start();

// include "connect.php";

    unset($_SESSION['customer']);
    $pdo = getPDO();
    $sql = $pdo->prepare('select * from gg_users where  mailaddress=? and password=?');
    $sql->execute([h($_REQUEST['email']),h($_REQUEST['password'])]);

    foreach($sql as $row){
        $_SESSION['customer']=[
            'user_id'        => $row['user_id'],
            'firstname'      => $row['firstname'],
            'lastname'       => $row['lastname'],
            'address'        => $row['address'],
            'login'          => $row["login_name"]
        ];
    }

    if(isset($_SESSION['customer'])){
        require './index.php';
        
        echo 'いらっしゃいませ ', $_SESSION['customer']['firstname'], 'さん';

    }else {
        echo 'ログイン名またはパスワードが違います。';
    }


?>

              <!-- taro.tanaka@example.com -->
            <!-- $2y$10$examplehashedpassword1 -->