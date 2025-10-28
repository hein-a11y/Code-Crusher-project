<?php 
session_start();

header('Content-Type: text/html; charset=utf-8');

if(!isset($_SESSION['user_id'])){
    echo <<< HTML
        <h1>Error</h1>
        <h3>You must be logged in to submit review</h3>
        <h3><a href="#">To login page</a></h3>
    HTML;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>


