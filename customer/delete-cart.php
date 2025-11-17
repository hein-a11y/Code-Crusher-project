<?php
session_start();
// require_once '../header.php';
require_once '../functions.php';

// (2) ログインチェック（必須）
if (empty($_SESSION['customer']['user_id'])) {
    // ログインしていない（不正なアクセス）
    // JSON形式で「失敗」を返す
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// ログイン中のユーザーID
$user_id = $_SESSION['customer']['user_id'];

// (3) JSから送られてきたカートIDを取得
$cart_id_to_delete = $_POST['cart_id'] ?? null;

if (empty($cart_id_to_delete)) {
    // 消すIDが指定されていない
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID missing']);
    exit();
}

// (4) データベースから削除 (DELETE)
try {
    $pdo = getPDO(); // functions.php の getPDO() を呼び出す
    
    // 【重要】必ず「cart_id」と「user_id」の両方が一致する行だけを削除
    // (他人のカートアイテムを削除できてしまう脆弱性を防ぐため)
    $sql = $pdo->prepare(
        "DELETE FROM gg_carts WHERE cart_id = ? AND user_id = ?"
    );
    
    // 実行
    $sql->execute([
        $cart_id_to_delete,
        
        $user_id
    ]);

    // (5) JSに「成功」を伝える
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // (6) DBエラーが起きたら「失敗」を伝える
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

exit();
?>