<?php require '../functions.php' ?>
<?php
$gadget_name = $_REQUEST['gadget_name'];
$gadget_explanation = $_REQUEST['gadget_explanation'];
$category_id = $_REQUEST['category_id'];
$new_category = $_REQUEST['new_category'] ?? '';
$manufacturer = $_REQUEST['manufacturer'];
$price = $_REQUEST['price'];
$stock = $_REQUEST['stock'];

$spec_names = $_REQUEST['spec_name'] ?? [];
$spec_custom_names = $_REQUEST['spec_custom_name'] ?? [];
$spec_values = $_REQUEST['spec_value'] ?? [];
$spec_units = $_REQUEST['spec_unit'] ?? [];

$pdo = getPDO();
// トランザクション開始（途中でエラーが出たら全て取り消せるようにする）
$pdo->beginTransaction();

// -------------------------------------------------------
// 1. カテゴリの処理
// -------------------------------------------------------
if (!empty($new_category)) {
    $sql = $pdo->prepare("SELECT * FROM gg_category WHERE category_name = ?");
    $sql->execute([$new_category]);
    if ($sql->fetch()) {
        die('既存のカテゴリー名と重複しています。');
    }
    $sql = $pdo->prepare("INSERT INTO gg_category VALUES (?,?)");
    $sql->execute([NULL, $new_category]);
    $category_id = $pdo->lastInsertId();
}

// -------------------------------------------------------
// 2. ガジェット本体の登録
// -------------------------------------------------------
$sql = $pdo->prepare("INSERT INTO gg_gadget VALUES (?,?,?,?,?,?,?,?,?,?)");
$sql->execute([NULL, $category_id, $gadget_name, $gadget_explanation, $manufacturer, $price, $stock, 1, date('Y-m-d H:i:s'), NULL]);
$gadget_id = $pdo->lastInsertId();

// -------------------------------------------------------
// 3. スペック情報の登録
// -------------------------------------------------------
if (is_array($spec_names)) {
    foreach ($spec_names as $i => $spec_identifier) {

        $target_spec_id = null;
        $current_value = $spec_values[$i] ?? '';

        // 値が空ならスキップ（必要に応じて）
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
                    // 新規仕様登録
                    $sql = $pdo->prepare("INSERT INTO gg_specifications VALUES (?,?,?,?)");
                    $sql->execute([NULL, $custom_name, $custom_unit, 'GADGET']);
                    $target_spec_id = $pdo->lastInsertId();
                }
        } else {
            // B. 既存の仕様が選ばれている場合
            $target_spec_id = $spec_identifier;
        }
        // スペック値の登録
        if ($target_spec_id) {
            $sql = $pdo->prepare("INSERT INTO gg_gadget_specs VALUES (?,?,?,?)");
            $sql->execute([NULL, $gadget_id, $target_spec_id, $current_value]);
        
        }
    }
}

// -------------------------------------------------------
// 4. 画像のアップロード処理
// -------------------------------------------------------
$upload_dir = '../customer/gadget-images/';

// ディレクトリがなければ作成
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['gadget_images'])) {
    $files = $_FILES['gadget_images'];
    $count = count($files['name']); // アップロードされたファイル数

    for ($i = 0; $i < $count; $i++) {
        // エラーチェック
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            
            // ファイル情報の取得
            $tmp_name = $files['tmp_name'][$i];
            $original_name = basename($files['name'][$i]);
            
            // 安全なファイル名を生成 (例: gadgets-ID_連番.jpg)
            // ※拡張子の取得
            $ext = pathinfo($original_name, PATHINFO_EXTENSION);
            // ファイル名: gadgets-{gadget_id}_{連番}.{拡張子}
            $new_file_name = "gadgets-{$gadget_id}_" . ($i + 1) . ".{$ext}";
            $target_path = $upload_dir . $new_file_name;
            
            // DB保存用のパス (customerフォルダからの相対パス等を想定)
            $db_path = "./gadget-images/" . $new_file_name;

            // ファイルを一時フォルダから保存先へ移動
            if (move_uploaded_file($tmp_name, $target_path)) {
                // 3. 画像情報をDBに登録 (gg_mediaテーブル)
                $sql = $pdo->prepare("INSERT INTO gg_media VALUES (?,?,?,?,?,?)");
                $sql->execute([NULL, NULL, $gadget_id, $db_path, 'image', ($i === 0) ? 1 : 0]);
            }
        }
    }
}






?>