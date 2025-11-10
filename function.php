<?php
function getPDO() {
    // データベースの設定
    // $host = '192.168.25.128';
    $host = 'localhost';
    $dbname = 'gg_store';
    $id = 'crushers';
    $password = 'crushggs@2025';

    // db_ggstoreデータベースに接続するだけの処理
    return new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $id, $password);
}

function debug($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function h($c){
    return htmlspecialchars($c);
}

?>