<?php require './functions.php'; ?>
<?php
header('Content-Type: application/json; charset=utf-8');
$keyword = h($_GET['keyword']) ?? '';

// バリデーション
if (empty($keyword)) {
    echo json_encode([
        'status' => 'redirect',
        'action' => './index.php',
        'method' => 'POST'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo = getPDO();
$encoded_keyword = urlencode($keyword);

// --- 1. ガジェットを検索 ---
$sql = $pdo->prepare('SELECT gadget_name, gadget_explanation, manufacturer, connectivity_type FROM gg_gadget WHERE gadget_name LIKE ?');
$sql->execute(['%' . $keyword . '%']);

if ($sql->fetch(PDO::FETCH_ASSOC)) { // 1件でもヒットしたら
    echo json_encode([
        'status' => 'redirect',
        'action' => './gadgets.php?keyword=' . $encoded_keyword,
        'method' => 'GET'
    ], JSON_UNESCAPED_UNICODE);
    exit;
} 

// --- 2. ゲームを検索 ---
// (ガジェットになかった場合のみ実行される)
$sql = $pdo->prepare('SELECT game_name, manufacturer, game_type FROM gg_game WHERE game_name LIKE ?');
$sql->execute(['%' . $keyword . '%']);

if ($sql->fetch(PDO::FETCH_ASSOC)) { // 1件でもヒットしたら
    echo json_encode([
        'status' => 'redirect',
        'action' => './games.php?keyword=' . $encoded_keyword,
        'method' => 'GET'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// どちらにもヒットしなかった場合の応答を追加
echo json_encode([
    'status' => 'redirect',
    'action' => './not-found.php?_keyword=' . $encoded_keyword,
    'method' => 'GET'
], JSON_UNESCAPED_UNICODE);


?>