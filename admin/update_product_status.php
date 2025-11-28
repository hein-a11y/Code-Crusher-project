<?php
require '../functions.php';

// JSONレスポンスを返す設定
header('Content-Type: application/json');

// POSTリクエスト以外は拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// JSONデータの受け取り
$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$type = $input['type'] ?? null; // 'game' or 'gadget'
$status = $input['status'] ?? null; // 1 (ON) or 0 (OFF)

// パラメータチェック
if ($id === null || $type === null || $status === null) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

try {
    $pdo = getPDO();
    $sql = "";

    // 商品タイプに応じて更新するテーブルを切り替え
    if ($type === 'game') {
        $sql = "UPDATE gg_game SET Sales_Status = :status WHERE game_id = :id";
    } elseif ($type === 'gadget') {
        $sql = "UPDATE gg_gadget SET Sales_Status = :status WHERE gadget_id = :id";
    } else {
        throw new Exception('Invalid product type');
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // エラー時は500エラーなどを返さず、JSONでエラー内容を返す
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>