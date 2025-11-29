<?php
require '../functions.php';

// 結果表示用の変数
$status = ''; // 'success' or 'error'
$message = '';
$error_detail = '';
$registered_data = [];

// フォームからの入力を取得
$game_name = $_REQUEST['game_name'] ?? '';
$game_explanation = $_REQUEST['game_explanation'] ?? '';
$manufacturer = $_REQUEST['manufacturer'] ?? '';
$price = $_REQUEST['price'] ?? '';
$game_type = $_REQUEST['game_type'] ?? ''; 

// 在庫数は空の場合NULLとする
$stock = $_REQUEST['stock'] ?? '';
if ($stock === '') {
    $stock = null;
}

// プラットフォーム
$platform_id = $_REQUEST['platform_id'] ?? '';
$new_platform = $_REQUEST['new_platform'] ?? '';

// ジャンル (複数)
$genre_ids = $_REQUEST['genre_ids'] ?? [];
$new_genre = $_REQUEST['new_genre'] ?? '';

// 動作環境 (最低)
$min_req_ids = $_REQUEST['min_req_id'] ?? [];
$min_req_customs = $_REQUEST['min_req_custom'] ?? [];
$min_req_vals_sel = $_REQUEST['min_req_value_select'] ?? [];
$min_req_vals_cus = $_REQUEST['min_req_value_custom'] ?? [];

// 動作環境 (推奨)
$rec_req_ids = $_REQUEST['rec_req_id'] ?? [];
$rec_req_customs = $_REQUEST['rec_req_custom'] ?? [];
$rec_req_vals_sel = $_REQUEST['rec_req_value_select'] ?? [];
$rec_req_vals_cus = $_REQUEST['rec_req_value_custom'] ?? [];

try {
    $pdo = getPDO();
    // トランザクション開始
    $pdo->beginTransaction();

    // -------------------------------------------------------
    // 1. プラットフォームの処理
    // -------------------------------------------------------
    if ($platform_id === 'new' || (!empty($new_platform) && empty($platform_id))) {
        if (empty($new_platform)) throw new Exception('新規プラットフォーム名が入力されていません。');
        
        $sql = $pdo->prepare("SELECT * FROM gg_platforms WHERE platform_name = ?");
        $sql->execute([$new_platform]);
        if ($sql->fetch()) throw new Exception('既存のプラットフォーム名と重複しています。');
        
        $sql = $pdo->prepare("INSERT INTO gg_platforms VALUES (NULL, ?)");
        $sql->execute([$new_platform]);
        $platform_id = $pdo->lastInsertId();
    }
    
    if (empty($platform_id) || $platform_id === 'new') throw new Exception('プラットフォームが正しく選択されていません。');

    // -------------------------------------------------------
    // 2. ジャンルの処理 (複数対応)
    // -------------------------------------------------------
    if (empty($genre_ids)) throw new Exception('ジャンルが選択されていません。');

    // "その他"(new)が選択されているかチェック
    if (in_array('new', $genre_ids)) {
        if (empty($new_genre)) throw new Exception('新規ジャンル名が入力されていません。');

        $sql = $pdo->prepare("SELECT * FROM gg_genres WHERE genre_name = ?");
        $sql->execute([$new_genre]);
        if ($sql->fetch()) throw new Exception('既存のジャンル名と重複しています。');
        
        $sql = $pdo->prepare("INSERT INTO gg_genres VALUES (NULL, ?)");
        $sql->execute([$new_genre]);
        $new_genre_id = $pdo->lastInsertId();

        $genre_ids = array_diff($genre_ids, ['new']);
        $genre_ids[] = $new_genre_id;
    }

    // -------------------------------------------------------
    // 3. ゲーム本体の登録
    // -------------------------------------------------------
    $sql = $pdo->prepare("INSERT INTO gg_game VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 1, ?, NULL)");
    $sql->execute([$game_name, $game_explanation, $platform_id, $manufacturer, $game_type, $price, $stock, date('Y-m-d H:i:s')]);
    $game_id = $pdo->lastInsertId();

    // -------------------------------------------------------
    // 4. ゲームとジャンルの紐付け
    // -------------------------------------------------------
    $sql = $pdo->prepare("INSERT INTO gg_game_genres VALUES (?, ?)");
    foreach ($genre_ids as $gid) {
        $sql->execute([$game_id, $gid]);
    }

    // -------------------------------------------------------
    // 5. 動作環境(スペック)の登録
    // -------------------------------------------------------
    function insertRequirements($pdo, $game_id, $ids, $customs, $val_selects, $val_customs, $type) {
        if (!is_array($ids)) return;
        foreach ($ids as $i => $spec_identifier) {
            $val_select = $val_selects[$i] ?? '';
            $val_custom = $val_customs[$i] ?? '';
            $current_value = ($val_select === 'new' || $val_select === '') ? $val_custom : $val_select;

            if ($current_value === '') continue;

            $custom_name = $customs[$i] ?? '';
            $target_spec_id = null;

            if (!empty($custom_name)) {
                $sql = $pdo->prepare("SELECT spec_id FROM gg_specifications WHERE spec_name = ?");
                $sql->execute([$custom_name]);
                $existing = $sql->fetch();
                if ($existing) {
                    $target_spec_id = $existing['spec_id'];
                } else {
                    $sql = $pdo->prepare("INSERT INTO gg_specifications VALUES (NULL, ?, NULL, 'GAME')");
                    $sql->execute([$custom_name]);
                    $target_spec_id = $pdo->lastInsertId();
                }
            } else {
                $target_spec_id = $spec_identifier;
            }

            if ($target_spec_id && $target_spec_id !== 'other') {
                $sql = $pdo->prepare("INSERT INTO gg_game_requirements VALUES (NULL, ?, ?, ?, ?)");
                $sql->execute([$game_id, $target_spec_id, $current_value, $type]);
            }
        }
    }

    insertRequirements($pdo, $game_id, $min_req_ids, $min_req_customs, $min_req_vals_sel, $min_req_vals_cus, 'MIN');
    insertRequirements($pdo, $game_id, $rec_req_ids, $rec_req_customs, $rec_req_vals_sel, $rec_req_vals_cus, 'REC');

    // -------------------------------------------------------
    // 6. 画像のアップロード処理
    // -------------------------------------------------------
    $upload_dir = '../customer/game-images/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    $uploaded_images_count = 0;
    if (isset($_FILES['game_images'])) {
        $files = $_FILES['game_images'];
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmp_name = $files['tmp_name'][$i];
                $original_name = basename($files['name'][$i]);
                $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                $new_file_name = "games-{$game_id}_" . ($i + 1) . ".{$ext}";
                $target_path = $upload_dir . $new_file_name;
                $db_path = "./game-images/" . $new_file_name;

                if (move_uploaded_file($tmp_name, $target_path)) {
                    $sql = $pdo->prepare("INSERT INTO gg_media VALUES (NULL, ?, NULL, ?, 'image', ?)");
                    $sql->execute([$game_id, $db_path, ($i === 0) ? 1 : 0]);
                    $uploaded_images_count++;
                }
            }
        }
    }

    $pdo->commit();
    $status = 'success';
    $message = 'ゲームの登録が完了しました。';
    $registered_data = [
        'ID' => $game_id,
        'タイトル' => $game_name,
        '価格' => '¥' . number_format($price),
        '画像数' => $uploaded_images_count . '枚'
    ];

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $status = 'error';
    $message = '登録中にエラーが発生しました。';
    $error_detail = $e->getMessage();
}
?>

<?php require_once '../admin_header.php'; ?>

<link rel="stylesheet" href="./css/gadget-insert.css">
        align-items: center;
    }
    .btn i { margin-right: 8px; }
    
    .btn-primary {
        background-color: var(--accent-blue);
        color: var(--bg-dark);
    }
    .btn-primary:hover { background-color: var(--accent-hover); }
    
    .btn-secondary {
        background-color: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
    }
    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
    }
</style>

<div class="main-content">
    <header class="header">
        <button id="sidebar-toggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
        <div class="header-search"></div> </header>

    <main class="page-content">
        <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

        <section class="admin-page">
            <div class="card result-container">
                
                <?php if ($status === 'success'): ?>
                    <div class="result-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="result-title"><?php echo h($message); ?></h2>
                    
                    <div class="result-detail">
                        <?php foreach ($registered_data as $key => $val): ?>
                            <p><?php echo h($key); ?>: <strong><?php echo h($val); ?></strong></p>
                        <?php endforeach; ?>
                    </div>

                    <div class="action-buttons">
                        <a href="admin_products.php" class="btn btn-secondary">
                            <i class="fas fa-list"></i> 商品一覧へ
                        </a>
                        <a href="admin_add_game.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> 続けて登録
                        </a>
                    </div>

                <?php else: ?>
                    <div class="result-icon error">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2 class="result-title"><?php echo h($message); ?></h2>
                    
                    <div class="error-text">
                        <i class="fas fa-bug"></i> 詳細: <?php echo h($error_detail); ?>
                    </div>

                    <div class="action-buttons">
                        <button onclick="history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> 戻って修正する
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    </main>
</div>

<script>
    // サイドバー用スクリプト (admin_add_game.php等と同様)
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarClose = document.getElementById('sidebar-close');

    function toggleSidebar() {
        sidebar.classList.toggle('is-open');
        sidebarOverlay.classList.toggle('hidden');
    }

    if(sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
    if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    if(sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
</script>
</body>
</html>