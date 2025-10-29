<?php header('Content-Type: application/json; charset=utf-8');
$pdo = new PDO('mysql:host=localhost;dbname=gg_store;charset=utf8', 'crushers', 'crushggs@2025');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        
        if(isset($_POST['keyword'])) {
            $sql = $pdo->prepare('SELECT * FROM gg_gadget WHERE name LIKE ?');
            $sql->execute(['%' . $_POST['keyword'] . '%']);
            if($sql->fetchAll(PDO::FETCH_ASSOC)) {
                echo <<< HTML
                <script>
                    window.location.href = 'gadgets.php?=keyword={$_POST['keyword']}';
                </script>
                HTML;
            } else {
            echo "キーワードが入力されていません。";
        }
    }

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
    $keyword = $data['keyword'] ?? '';

    // バリデーション
    if (empty($keyword)) {
        echo <<< HTML
        <script>
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php';
        </script>
        HTML;
    }


    ?>
</body>
</html>
