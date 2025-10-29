<?php
// JSON形式でレスポンスを返す設定
header('Content-Type: application/json; charset=utf-8');

// CORSを許可する場合（必要に応じて）
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type');

// POSTメソッドのみ受け付ける
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error',
        'message' => 'POSTメソッドのみ許可されています'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// JavaScriptから送られたJSONデータを取得
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// JSONのパースに失敗した場合
if ($data === null) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => 'error',
        'message' => '不正なJSON形式です'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// データを取得
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$age = $data['age'] ?? 0;

// バリデーション
if (empty($name) || empty($email)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => '名前とメールアドレスは必須です'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// メールアドレスの形式チェック
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => '有効なメールアドレスを入力してください'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// データベースに保存（例）
try {
    // 実際のデータベース接続例
    // $pdo = new PDO('mysql:host=localhost;dbname=mydb;charset=utf8mb4', 'username', 'password');
    // $stmt = $pdo->prepare('INSERT INTO users (name, email, age) VALUES (?, ?, ?)');
    // $stmt->execute([$name, $email, $age]);
    // $userId = $pdo->lastInsertId();
    
    // この例ではダミーのユーザーIDを生成
    $userId = rand(1000, 9999);
    
    // 成功レスポンス
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'ユーザーを登録しました',
        'userId' => $userId,
        'data' => [
            'name' => $name,
            'email' => $email,
            'age' => $age
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'サーバーエラーが発生しました'
    ], JSON_UNESCAPED_UNICODE);
}
?>