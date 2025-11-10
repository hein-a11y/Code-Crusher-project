<?php

include "connect.php";

    unset($_SESSION['customer']);
    $pdo = getPDO();
    $sql = $pdo->prepare('select * from customer where login=? and password=?');
    $sql->execute([h($_REQUEST['login']),h($_REQUEST['password'])]);
    foreach($sql as $row){
        $_SESSION['customer']=[
            'id' => $row['id'], 'name'=> $row['name'],
            'address' => $row['address'], 'login' => $row["login"],
            'password' => $row['password']];
            }


if(isset($_POST['signUp'])){
    $firstName=$_POST['fName'];
    $lastName=$_POST['lName'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

    



}

?>