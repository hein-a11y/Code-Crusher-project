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

// パラメータチェック
if ($id === null || $type === null) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$pdo = getPDO();
$pdo->beginTransaction();

try {
    // ---------------------------------------------------
    // 1. 画像ファイルの削除準備
    // ---------------------------------------------------
    // 削除対象の画像パスを取得
    $sql_media = "";
    if ($type === 'game') {
        $sql_media = "SELECT url FROM gg_media WHERE game_id = ?";
    } elseif ($type === 'gadget') {
        $sql_media = "SELECT url FROM gg_media WHERE gadget_id = ?";
    }

    $stmt = $pdo->prepare($sql_media);
    $stmt->execute([$id]);
    $media_files = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // ---------------------------------------------------
    // 2. データベースからの削除
    // ---------------------------------------------------
    // 外部キー制約（ON DELETE CASCADEがないテーブル）があるため、子テーブルから順に削除
    
    if ($type === 'game') {
        // 画像データ削除
        $pdo->prepare("DELETE FROM gg_media WHERE game_id = ?")->execute([$id]);
        
        // ジャンル紐付け削除
        $pdo->prepare("DELETE FROM gg_game_genres WHERE game_id = ?")->execute([$id]);
        
        // スペック要件削除 (CASCADE設定がある場合は自動だが、念のため)
        $pdo->prepare("DELETE FROM gg_game_requirements WHERE game_id = ?")->execute([$id]);
        
        // ゲーム本体削除
        $stmt = $pdo->prepare("DELETE FROM gg_game WHERE game_id = ?");
        $stmt->execute([$id]);

    } elseif ($type === 'gadget') {
        // 画像データ削除
        $pdo->prepare("DELETE FROM gg_media WHERE gadget_id = ?")->execute([$id]);
        
        // スペック値削除
        $pdo->prepare("DELETE FROM gg_gadget_specs WHERE gadget_id = ?")->execute([$id]);
        
        // ガジェット本体削除
        $stmt = $pdo->prepare("DELETE FROM gg_gadget WHERE gadget_id = ?");
        $stmt->execute([$id]);
    }

    // 削除対象が存在しなかった場合
    if ($stmt->rowCount() === 0) {
        throw new Exception('対象の商品が見つかりませんでした。削除済みか、IDが不正です。');
    }

    // ---------------------------------------------------
    // 3. サーバー上の画像ファイルを削除
    // ---------------------------------------------------
    // DB削除が成功してからファイルを消す
    foreach ($media_files as $file_path) {
        // パスは "gadget-images/filename.jpg" のようにDBに入っている想定
        // 実際のパスに合わせて調整 (customerフォルダ内にあると仮定)
        $full_path = '../customer/' . $file_path;
        
        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    // 外部キー制約エラーなどの場合
    if ($e->getCode() == '23000') {
        $message = 'この商品は注文履歴などに使用されているため削除できません。';
    } else {
        $message = $e->getMessage();
    }
    echo json_encode(['success' => false, 'message' => $message]);
}
?>