<?php
require '../functions.php';

// 結果表示用の変数
$status = ''; // 'success' or 'error'
$message = '';
$error_detail = '';
$registered_data = [];

// フォームからの入力を取得
$gadget_name = $_REQUEST['gadget_name'] ?? '';
$gadget_explanation = $_REQUEST['gadget_explanation'] ?? '';
$category_id = $_REQUEST['category_id'] ?? '';
$new_category = $_REQUEST['new_category'] ?? '';
$manufacturer = $_REQUEST['manufacturer'] ?? '';
$price = $_REQUEST['price'] ?? '';
$stock = $_REQUEST['stock'] ?? '';

// スペック情報
$spec_names = $_REQUEST['spec_name'] ?? [];
$spec_custom_names = $_REQUEST['spec_custom_name'] ?? [];
$spec_values = $_REQUEST['spec_value'] ?? [];
$spec_units = $_REQUEST['spec_unit'] ?? [];

try {
    $pdo = getPDO();
    // トランザクション開始
    $pdo->beginTransaction();

    // -------------------------------------------------------
    // 1. カテゴリの処理
    // -------------------------------------------------------
    // "new" が選択された場合、または空で新規入力がある場合
    if ($category_id === 'new' || (!empty($new_category) && empty($category_id))) {
        if (empty($new_category)) {
            throw new Exception('新規カテゴリー名が入力されていません。');
        }

        $sql = $pdo->prepare("SELECT * FROM gg_category WHERE category_name = ?");
        $sql->execute([$new_category]);
        if ($sql->fetch()) {
            throw new Exception('既存のカテゴリー名と重複しています。');
        }

        $sql = $pdo->prepare("INSERT INTO gg_category VALUES (NULL, ?)");
        $sql->execute([$new_category]);
        $category_id = $pdo->lastInsertId();
    }

    if (empty($category_id) || $category_id === 'new') {
        throw new Exception('カテゴリーが正しく選択されていません。');
    }

    // -------------------------------------------------------
    // 2. ガジェット本体の登録
    // -------------------------------------------------------
    // Sales_Status = 1 (販売中) としています
    $sql = $pdo->prepare("INSERT INTO gg_gadget VALUES (NULL, ?, ?, ?, ?, ?, ?, 1, ?, NULL)");
    $sql->execute([
        $category_id,
        $gadget_name,
        $gadget_explanation,
        $manufacturer,
        $price,
        $stock,
        date('Y-m-d H:i:s')
    ]);
    $gadget_id = $pdo->lastInsertId();

    // -------------------------------------------------------
    // 3. スペック情報の登録
    // -------------------------------------------------------
    if (is_array($spec_names)) {
        foreach ($spec_names as $i => $spec_identifier) {
            $target_spec_id = null;
            $current_value = $spec_values[$i] ?? '';

            // 値が空ならスキップ
            if ($current_value === '') continue;

            $custom_name = $spec_custom_names[$i] ?? '';
            $custom_unit = $spec_units[$i] ?? '';

            // A. 「その他」が選ばれていて、新規入力がある場合
            if (!empty($custom_name)) {
                // 重複チェック
                $sql = $pdo->prepare("SELECT spec_id FROM gg_specifications WHERE spec_name = ?");
                $sql->execute([$custom_name]);
                $existing = $sql->fetch();
                
                if ($existing) {
                    $target_spec_id = $existing['spec_id'];
                } else {
                    // 新規仕様登録 (GADGETタイプ)
                    $sql = $pdo->prepare("INSERT INTO gg_specifications VALUES (NULL, ?, ?, 'GADGET')");
                    $sql->execute([$custom_name, $custom_unit]);
                    $target_spec_id = $pdo->lastInsertId();
                }
            } else {
                // B. 既存の仕様が選ばれている場合
                $target_spec_id = $spec_identifier;
            }

            // スペック値の登録
            if ($target_spec_id && $target_spec_id !== 'other') {
                $sql = $pdo->prepare("INSERT INTO gg_gadget_specs VALUES (NULL, ?, ?, ?)");
                $sql->execute([$gadget_id, $target_spec_id, $current_value]);
            }
        }
    }

    // -------------------------------------------------------
    // 4. 画像のアップロード処理
    // -------------------------------------------------------
    $upload_dir = '../customer/gadget-images/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $uploaded_images_count = 0;
    if (isset($_FILES['gadget_images'])) {
        $files = $_FILES['gadget_images'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                
                $tmp_name = $files['tmp_name'][$i];
                $original_name = basename($files['name'][$i]);
                $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                
                // ファイル名: gadgets-{gadget_id}_{連番}.{拡張子}
                $new_file_name = "gadgets-{$gadget_id}_" . ($i + 1) . ".{$ext}";
                $target_path = $upload_dir . $new_file_name;
                $db_path = "./gadget-images/" . $new_file_name;

                if (move_uploaded_file($tmp_name, $target_path)) {
                    $sql = $pdo->prepare("INSERT INTO gg_media VALUES (NULL, NULL, ?, ?, 'image', ?)");
                    $sql->execute([$gadget_id, $db_path, ($i === 0) ? 1 : 0]);
                    $uploaded_images_count++;
                }
            }
        }
    }

    $pdo->commit();
    $status = 'success';
    $message = 'ガジェットの登録が完了しました。';
    $registered_data = [
        'ID' => $gadget_id,
        '製品名' => $gadget_name,
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

<style>
    /* 結果ページの独自スタイル */
    .result-container {
        max-width: 600px;
        margin: 40px auto;
        text-align: center;
        padding: 40px 20px;
    }
    .result-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }
    .result-icon.success { color: var(--green); }
    .result-icon.error { color: var(--red); }
    
    .result-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }
    
    .result-detail {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        text-align: left;
        border: 1px solid var(--border-color);
    }
    .result-detail p {
        margin-bottom: 8px;
        color: var(--text-secondary);
    }
    .result-detail strong {
        color: var(--text-primary);
        margin-left: 8px;
    }
    .error-text {
        color: var(--red);
        background-color: rgba(248, 113, 113, 0.1);
        padding: 15px;
        border-radius: 4px;
        margin-top: 10px;
        text-align: left;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 30px;
    }
    .btn {
        padding: 10px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.2s;
        display: inline-flex;
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
                        <a href="admin_add_gadget.php" class="btn btn-primary">
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
    // サイドバー用スクリプト
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