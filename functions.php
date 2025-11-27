<?php
// function getPDO() {
//     // データベースの共通設定
//     $dbname = 'gg_store';
//     $id = 'crushers';
//     $password = 'crushggs@2025';

//     // ホストの設定
//     $primaryHost = '192.168.25.128'; // 優先するIP
//     $fallbackHost = 'localhost';      // 接続できなかった場合のローカルホスト

//     // PDOオプション
//     // ATTR_TIMEOUTを設定しないと、IPに繋がらない場合にデフォルト（通常30~60秒）の待機時間が発生してしまいます
//     $options = [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         PDO::ATTR_TIMEOUT => 2, // 2秒で接続できなければ諦める設定
//     ];

//     try {
//         // --- 1. 優先IPへの接続を試みる ---
//         $dsn = "mysql:host={$primaryHost};dbname={$dbname};charset=utf8";
//         return new PDO($dsn, $id, $password, $options);

//     } catch (PDOException $e) {
//         // --- 2. 失敗した場合、localhostへの接続を試みる ---
//         try {
//             $dsn = "mysql:host={$fallbackHost};dbname={$dbname};charset=utf8";
//             return new PDO($dsn, $id, $password, $options);
//         } catch (PDOException $e2) {
//             // 両方失敗した場合は、最初のエラーか2番目のエラーを投げます（ここでは2番目）
//             throw $e2;
//         }
//     }
// }

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

function h($string) {
    return htmlspecialchars($string);
}

function debug($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function sql_debug(){
    echo "<pre>";
    $sql->debugDumpParams();
    echo "</pre>";
}
?>