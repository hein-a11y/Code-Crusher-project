<?php session_start(); ?>

<?php 
if(isset($_SESSION['customer'])){
    unset($_SESSION['customer']);

    header("location: index.php");
    exit;
}else {
    echo 'すでにログアウトしています。';
}
?>


