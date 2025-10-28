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
?>