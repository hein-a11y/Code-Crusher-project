<?php
function getPDO() {
    // データベースの設定
    $host = 'localhost';
    $dbname = 'gg_store';
    $id = 'crushers';
    $password = 'crushggs@2025';

    // db_ggstoreデータベースに接続するだけの処理
    return new PDO("mysql:host={$host};dbname={$dbname};charset=utf8",
                    $id, $password);
}

function debug($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function h($string){
    return htmlspecialchars($string);
}

function game_name($id){
    $pdo = getPDO();
    $sql = $pdo->prepare("SELECT game_name FROM gg_game WHERE game_id=?");
    $sql -> execute([(int)$id]);
    $gameName = $sql->fetchAll();

    return $gameName[0]['game_name'];
}

function gadget_name($id){
    $pdo = getPDO();
    $sql = $pdo->prepare("SELECT gadget_name FROM gg_gadget WHERE gadget_id=?");
    $sql -> execute([(int)$id]);
    $gadgetName = $sql->fetchAll();

    return $gadgetName[0]['gadget_name'];
}
?>