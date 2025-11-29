<?php require_once '../admin_header.php'; ?>
<?php require '../functions.php'; ?>

<?php
$gadget_id = $_GET['id'] ?? null;
if (!$gadget_id) { header('Location: admin_products.php'); exit; }

$pdo = getPDO();
// ガジェット情報取得
$sql = $pdo->prepare("SELECT * FROM gg_gadget WHERE gadget_id = ?");
$sql->execute([$gadget_id]);
$gadget = $sql->fetch(PDO::FETCH_ASSOC);
if (!$gadget) die("ガジェットが見つかりません。");

// スペック取得
$sql = $pdo->prepare("SELECT gs.*, s.spec_name, s.unit FROM gg_gadget_specs gs JOIN gg_specifications s ON gs.spec_id = s.spec_id WHERE gs.gadget_id = ?");
$sql->execute([$gadget_id]);
$current_specs = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="./css/admin_edit_gadget.css">

<div class="main-content">
    <header class="header">
        <button id="sidebar-toggle" class="sidebar-toggle-btn"><i class="fas fa-bars"></i></button>
        <div class="header-search"></div>
    </header>

    <main class="page-content">
        <div id="sidebar-overlay" class="sidebar-overlay hidden"></div>

        <section class="admin-page">
            <h2 class="page-title">ガジェット編集: <?php echo h($gadget['gadget_name']); ?></h2>
            
            <form action="./gadget_update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="gadget_id" value="<?php echo h($gadget['gadget_id']); ?>">

                <div class="card">
                    <h3 class="card-title">ガジェット情報</h3>
                    
                    <div class="form-group">
                        <label for="gadget_name" class="form-label">ガジェット名</label>
                        <input type="text" id="gadget_name" name="gadget_name" class="form-input" required value="<?php echo h($gadget['gadget_name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="gadget_explanation" class="form-label">ガジェット説明</label>
                        <textarea id="gadget_explanation" name="gadget_explanation" class="form-textarea"><?php echo h($gadget['gadget_explanation']); ?></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="category_id" class="form-label">カテゴリ</label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <option value="">選択してください...</option>
                                <?php
                                $sql = $pdo->query('SELECT * FROM gg_category ORDER BY category_id ASC');
                                foreach ($sql as $row) {
                                    $id = h($row['category_id']);
                                    $name = h($row['category_name']);
                                    $selected = ($id == $gadget['category_id']) ? 'selected' : '';
                                    echo <<<HTML
                                    <option value="{$id}" {$selected}>{$name}</option>
                                    HTML;
                                }
                                ?>
                                <option value="new">その他</option>
                            </select>
                            <div id="new_category_wrapper" class="custom-input-wrapper">
                                <input type="text" id="new_category" name="new_category" class="form-input" placeholder="新規カテゴリ名">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="manufacturer" class="form-label">メーカー</label>
                            <input type="text" id="manufacturer" name="manufacturer" class="form-input" value="<?php echo h($gadget['manufacturer']); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="price" class="form-label">価格 (円)</label>
                            <input type="number" id="price" name="price" class="form-input" required value="<?php echo h($gadget['price']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="stock" class="form-label">在庫数</label>
                            <input type="number" id="stock" name="stock" class="form-input" required value="<?php echo h($gadget['stock']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">スペック情報</label>
                        
                        <template id="spec-options-template">
                            <option value="">項目を選択...</option>
                            <?php
                            $spec_opts = $pdo->query('SELECT * FROM gg_specifications ORDER BY spec_id ASC')->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($spec_opts as $row) {
                                echo <<<HTML
                                <option value="{$row['spec_id']}" data-unit="{$row['unit']}">{$row['spec_name']}</option>
                                HTML;
                            }
                            ?>
                            <option value="other" data-unit="">その他</option>
                        </template>

                        <div id="specs-container">
                            <?php foreach ($current_specs as $spec): ?>
                                <div class="spec-row">
                                    <div class="spec-input-wrapper">
                                        <select name="spec_name[]" class="form-select spec-select" style="width: 100%;">
                                            <option value="">項目を選択...</option>
                                            <?php foreach ($spec_opts as $opt): ?>
                                                <?php $selected = ($opt['spec_id'] == $spec['spec_id']) ? 'selected' : ''; ?>
                                                <option value="<?= $opt['spec_id'] ?>" data-unit="<?= h($opt['unit']) ?>" <?= $selected ?>>
                                                    <?= h($opt['spec_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="other">その他</option>
                                        </select>
                                        <input type="text" name="spec_custom_name[]" class="form-input spec-custom-input" placeholder="項目名">
                                    </div>
                                    <input type="text" name="spec_value[]" class="form-input flex-grow-2" placeholder="値" value="<?= h($spec['spec_value']) ?>">
                                    <input type="text" name="spec_unit[]" class="form-input flex-grow-1 spec-unit" placeholder="単位" value="<?= h($spec['unit']) ?>">
                                    <button type="button" class="remove-spec-btn"><i class="fas fa-minus"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" id="add-spec-btn" class="button button-secondary button-dashed">
                            <i class="fas fa-plus"></i> スペックを追加
                        </button>
                    </div>
                </div> 
                
                <div class="card">
                    <button type="submit" class="button button-primary button-full">
                        <i class="fas fa-save"></i> 更新を保存する
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>
<script src="./js/admin_add_gadget.js"></script>
</body>
</html>