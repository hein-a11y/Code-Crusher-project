<?php
require '../functions.php';
$pdo = getPDO();
$sql = $pdo->query('SELECT gg_media.url FROM gg_media INNER JOIN gg_gadget ON gg_media.gadget_id = gg_gadget.gadget_id WHERE gg_gadget.gadget_id = 1');
$url = $sql->fetchAll(PDO::FETCH_ASSOC);
$img_src = h($url[0]['url']);
echo $img_src;
echo "\n";
echo "./gadget-images/gadgets-1_1.jpg";
echo <<< HTML
    <img src="{$img_src}" alt="商品画像">
    <img src="./gadget-images/gadgets-1_1.jpg" alt="商品画像">
HTML;
?>
