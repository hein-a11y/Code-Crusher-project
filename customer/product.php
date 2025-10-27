<?php require_once('../functions.php'); ?>
<form action="product.php" method="post">
    商品検索
    <input type="text" name="keyword">
    <input type="submit" value="検索">
</form>
<hr>
<table>
    <tr>
        <th>商品番号</th>
        <th>商品名</th>
        <th>価格</th>
    </tr>
<?php
// DB接続
$pdo = getPDO();

// キーワードが入力されているか・いないか判定
if(isset($_REQUEST['keyword'])) {
    // キーワードが入力されている場合の処理はここ
    // キーワードが含まれているname列(商品名)のデータに絞り込むSQLを実行する
    $sql = $pdo->prepare('SELECT * FROM product WHERE name LIKE ?');
    $sql->execute(['%' . $_REQUEST['keyword'] . '%']);
} else {
    // キーワードが入力されていない場合の処理はここ
    // すべての商品を表示するSQLを実行する
    $sql = $pdo->query('SELECT * FROM product');
}

// SQLの実行結果をすべて表示する
foreach($sql as $row) {
    $id = $row['id'];   // 商品番号
    echo <<< HTML
        <tr>
            <td>{$id}</td>
            <td><a href="detail.php?id={$id}">{$row['name']}</a></td>
            <td>{$row['price']}</td>
        </tr>

    HTML;
}
?>
</table>
<?php require_once('../footer.php'); ?>