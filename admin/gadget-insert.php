<?php require '../functions.php'; ?>
<?php
$gadget_name = $_REQUEST['gadget_name'] ?? "";
$gadget_explanation = $_REQUEST['gadget_explanation'] ?? "";
$category_id = $_REQUEST['category_id'] ?? "";
$new_category = $_REQUEST['new_category'] ?? "";
$manufacturer = $_REQUEST['manufacturer'] ?? "";
$price = $_REQUEST['price'] ?? "";
$stock = $_REQUEST['stock'] ?? "";
$spec_name = $_REQUEST['spec_name[]'] ?? "";
$spec_value = $_REQUEST['spec_value[]'] ?? "";
$spec_unit = $_REQUEST['spec_unit[]'] ?? "";

// $pdo = getPDO();


// if($category_id == 'new') {
//     $sql = $pdo -> prepare('INSERT INTO gg_category (category_id, category_name) VALUES (NULL, ?)');
//     $sql -> execute([$new_category]);
// }
?>