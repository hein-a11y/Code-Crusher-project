<?php
// (1) 必要なファイルを読み込む
require '../functions.php';

// セッションを開始（$_SESSION['user_id'] を使うため）
session_start();


//ログインチェック
// もしセッションに user_id が無ければ（ログインしていないなら）
if (empty($_SESSION['customer'])) {
    // ログインページに強制的に移動させる
    header('Location: login-input.php'); 
    exit();
}


// 送られてきたデータを変数に入れる
// ログイン中のユーザーID
$user_id = $_SESSION['customer']['user_id'];

// フォームから送られてきた商品IDと種類
// (issetで存在チェックをしてから取得)
$product_id = $_POST['product_id'] ?? null;
$product_type = $_POST['product_type'] ?? null;

// 数量 (今回はシンプルに「1」固定にします)
$quantity = 1; 


// データが正しいかチェック
// product_id が空、または type が 'game' 'gadget' 以外ならエラー
if (empty($product_id) || !in_array($product_type, ['game', 'gadget'])) {
    die("エラー：不正なリクエストです。");
}


//DBにINSERTするための準備
// gg_cartsテーブルの仕様に合わせて、game_idとgadget_idに振り分ける
$game_id_to_insert = NULL;
$gadget_id_to_insert = NULL;

if ($product_type === 'game') {
    // もし種類が 'game' なら、$game_id_to_insert に商品IDを入れる
    $game_id_to_insert = h($product_id);
    
} else if ($product_type === 'gadget') {
    // もし種類が 'gadget' なら、$gadget_id_to_insert に商品IDを入れる
    $gadget_id_to_insert = h($product_id);
}


//データベースに挿入
$pdo = getPDO();

// INSERT文の「準備」
// (user_id, game_id, gadget_id, quantity の順で ? に入れる)
$sql = $pdo->prepare(
    "INSERT INTO gg_carts (user_id, game_id, gadget_id, quantity) 
     VALUES (?, ?, ?, ?)"
);

// 「実行」
// 順番通りに $user_id, $game_id_to_insert, $gadget_id_to_insert, $quantity を渡す
$sql->execute([
    $user_id,
    $game_id_to_insert,
    $gadget_id_to_insert,
    $quantity
]);


//終わったらカートページに移動させる
// このPHPは処理だけを行うので、終わったらカートページに強制的に移動（リダイレクト）させる
header('Location: cart-input.php'); 
exit();

?>