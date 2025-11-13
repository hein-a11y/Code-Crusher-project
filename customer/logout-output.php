<?php session_start(); ?>

<?php 
if(isset($_SESSION['customer'])){
    unset($_SESSION['customer']);
    echo 'ログアウトしました。';

    header("location: index.php");
    exit;
}else {
    echo 'すでにログアウトしています。';
}
?>

<?php require '../header.php'; ?>
