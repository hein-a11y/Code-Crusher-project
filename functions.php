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

function isActiveMember(PDO $pdo, int $user_id): bool
{
    $sql = $pdo->prepare("SELECT 1 FROM gg_premium WHERE user_id = :user_id AND is_active = TRUE LIMIT 1");
    
    // Bind the user ID parameter
    $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
    //  Execute the query
    $sql->execute();
    
    // Check the result
    // fetchColumn() returns the value of the first column ('1' in this case) if a row is found, 
    // or FALSE if no rows are found. We cast the result to a boolean.
    return (bool) $sql->fetchColumn();
}

function hasBought(PDO $pdo, int $user_id, int $product_id,string $type): bool
{
    $sql = $pdo->prepare("SELECT orders.user_id,detail.game_id,detail.gadget_id FROM gg_detail_orders as detail
                             INNER JOIN gg_orders AS orders ON orders.order_id = detail.order_id
                              WHERE orders.user_id = :user_id AND detail.".$type." = :product_id;");
    
    // Bind the user ID parameter
    $sql->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $sql->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    //  Execute the query
    $sql->execute();
    
    // Check the result
    // fetchColumn() returns the value of the first column ('1' in this case) if a row is found, 
    // or FALSE if no rows are found. We cast the result to a boolean.
    return (bool) $sql->fetchColumn();
}

function datetime_to_date($dbDateTime){
    $dateTimeObject = new DateTime($dbDateTime);

        // Format the DateTime object to YYYY/MM/DD
    $formattedDate = $dateTimeObject->format('Y/m/d');

    return $formattedDate;
}
?>