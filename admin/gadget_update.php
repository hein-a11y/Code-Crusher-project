<?php require '../functions.php' ?>
<?php
$gadget_id = $_REQUEST['gadget_id'];
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
$pdo->beginTransaction();

try {
    // 1. カテゴリ処理
    if ($category_id === 'new' || (!empty($new_category) && empty($category_id))) {
        if (empty($new_category)) throw new Exception('新規カテゴリー名が空です');
        $sql = $pdo->prepare("INSERT INTO gg_category VALUES (NULL, ?)");
        $sql->execute([$new_category]);
        $category_id = $pdo->lastInsertId();
    }

    // 2. 本体更新
    $sql = $pdo->prepare("UPDATE gg_gadget SET category_id=?, gadget_name=?, gadget_explanation=?, manufacturer=?, price=?, stock=?, updated_time=NOW() WHERE gadget_id=?");
    $sql->execute([$category_id, $gadget_name, $gadget_explanation, $manufacturer, $price, $stock, $gadget_id]);

    // 3. スペック更新 (全削除 -> 再登録)
    $pdo->prepare("DELETE FROM gg_gadget_specs WHERE gadget_id=?")->execute([$gadget_id]);

    if (is_array($spec_names)) {
        foreach ($spec_names as $i => $spec_identifier) {
            $current_value = $spec_values[$i] ?? '';
            if ($current_value === '') continue;

            $custom_name = $spec_custom_names[$i] ?? '';
            $custom_unit = $spec_units[$i] ?? '';
            $target_spec_id = $spec_identifier;

            if ($target_spec_id === 'other' || empty($target_spec_id)) {
                if (!empty($custom_name)) {
                    $sql = $pdo->prepare("INSERT INTO gg_specifications VALUES (NULL, ?, ?, 'GADGET')");
                    $sql->execute([$custom_name, $custom_unit]);
                    $target_spec_id = $pdo->lastInsertId();
                } else { continue; }
            }

            $sql = $pdo->prepare("INSERT INTO gg_gadget_specs VALUES (NULL, ?, ?, ?)");
            $sql->execute([$gadget_id, $target_spec_id, $current_value]);
        }
    }

    $pdo->commit();
    header("Location: admin_products.php?msg=updated");

} catch (Exception $e) {
    $pdo->rollBack();
    echo "エラー: " . htmlspecialchars($e->getMessage());
}
?>