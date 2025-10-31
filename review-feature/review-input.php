<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');

if(!isset($_SESSION['customer'])){
    echo <<< HTML
        <h1>Error</h1>
        <h3>You must be logged in to submit review</h3>
        <h3><a href="#">To login page</a></h3>
    HTML;
}

$product_id = (int)($_GET['product_id']);

$user_id = $_SESSION['customer']['id'];
$username = $_SESSION['customer']['name'];

?>
<?php "../../header-input.php" ?>
<?php "../functions.php" ?>
<?php




?>


