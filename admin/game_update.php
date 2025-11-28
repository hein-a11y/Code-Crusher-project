<?php require '../functions.php' ?>
<?php
$game_id = $_REQUEST['game_id'];
$game_name = $_REQUEST['game_name'];
$game_explanation = $_REQUEST['game_explanation'];
$manufacturer = $_REQUEST['manufacturer'];
$price = $_REQUEST['price'];
$game_type = $_REQUEST['game_type'];
$stock = $_REQUEST['stock'] === '' ? null : $_REQUEST['stock'];

$platform_id = $_REQUEST['platform_id'];
$new_platform = $_REQUEST['new_platform'] ?? '';

$genre_ids = $_REQUEST['genre_ids'] ?? [];
$new_genre = $_REQUEST['new_genre'] ?? '';

// スペック受け取り
$min_req_ids = $_REQUEST['min_req_id'] ?? [];
$min_req_customs = $_REQUEST['min_req_custom'] ?? [];
$min_req_vals_sel = $_REQUEST['min_req_value_select'] ?? [];
$min_req_vals_cus = $_REQUEST['min_req_value_custom'] ?? [];

$rec_req_ids = $_REQUEST['rec_req_id'] ?? [];
$rec_req_customs = $_REQUEST['rec_req_custom'] ?? [];
$rec_req_vals_sel = $_REQUEST['rec_req_value_select'] ?? [];
$rec_req_vals_cus = $_REQUEST['rec_req_value_custom'] ?? [];

$pdo = getPDO();
$pdo->beginTransaction();

try {
    // 1. プラットフォーム処理
    if ($platform_id === 'new' || (!empty($new_platform) && empty($platform_id))) {
        if (empty($new_platform)) throw new Exception('新規プラットフォーム名が空です');
        $sql = $pdo->prepare("INSERT INTO gg_platforms VALUES (NULL, ?)");
        $sql->execute([$new_platform]);
        $platform_id = $pdo->lastInsertId();
    }

    // 2. ジャンル処理
    if (in_array('new', $genre_ids)) {
        if (empty($new_genre)) throw new Exception('新規ジャンル名が空です');
        $sql = $pdo->prepare("INSERT INTO gg_genres VALUES (NULL, ?)");
        $sql->execute([$new_genre]);
        $new_genre_id = $pdo->lastInsertId();
        $genre_ids = array_diff($genre_ids, ['new']);
        $genre_ids[] = $new_genre_id;
    }

    // 3. ゲーム本体更新
    $sql = $pdo->prepare("UPDATE gg_game SET game_name=?, game_explanation=?, platform_id=?, manufacturer=?, game_type=?, price=?, stock=?, updated_time=NOW() WHERE game_id=?");
    $sql->execute([$game_name, $game_explanation, $platform_id, $manufacturer, $game_type, $price, $stock, $game_id]);

    // 4. ジャンル紐付け更新 (一度全削除して登録し直す)
    $pdo->prepare("DELETE FROM gg_game_genres WHERE game_id=?")->execute([$game_id]);
    $stmt = $pdo->prepare("INSERT INTO gg_game_genres VALUES (?, ?)");
    foreach ($genre_ids as $gid) {
        $stmt->execute([$game_id, $gid]);
    }

    // 5. スペック更新 (一度全削除して登録し直す)
    $pdo->prepare("DELETE FROM gg_game_requirements WHERE game_id=?")->execute([$game_id]);
    
    function insertRequirements($pdo, $game_id, $ids, $customs, $val_selects, $val_customs, $type) {
        if (!is_array($ids)) return;
        foreach ($ids as $i => $spec_identifier) {
            $val_select = $val_selects[$i] ?? '';
            $val_custom = $val_customs[$i] ?? '';
            $current_value = ($val_select === 'new' || $val_select === '') ? $val_custom : $val_select;
            if ($current_value === '') continue;

            $custom_name = $customs[$i] ?? '';
            $target_spec_id = $spec_identifier;

            if ($target_spec_id === 'other' || empty($target_spec_id)) {
                if (!empty($custom_name)) {
                    $sql = $pdo->prepare("INSERT INTO gg_specifications VALUES (NULL, ?, NULL, 'GAME')");
                    $sql->execute([$custom_name]);
                    $target_spec_id = $pdo->lastInsertId();
                } else { continue; }
            }

            $sql = $pdo->prepare("INSERT INTO gg_game_requirements VALUES (NULL, ?, ?, ?, ?)");
            $sql->execute([$game_id, $target_spec_id, $current_value, $type]);
        }
    }
    insertRequirements($pdo, $game_id, $min_req_ids, $min_req_customs, $min_req_vals_sel, $min_req_vals_cus, 'MIN');
    insertRequirements($pdo, $game_id, $rec_req_ids, $rec_req_customs, $rec_req_vals_sel, $rec_req_vals_cus, 'REC');

    $pdo->commit();
    header("Location: admin_products.php?msg=updated");

} catch (Exception $e) {
    $pdo->rollBack();
    echo "エラー: " . htmlspecialchars($e->getMessage());
}
?>