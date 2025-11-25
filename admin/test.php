<?php
require '../functions.php';
$pdo = getPDO();

$sql = $pdo->query('SELECT * FROM gg_category ORDER BY category_id ASC');

debug($sql);

foreach ($sql as $row) {
    $id = $row['category_id'];
    $name = $row['category_name'];
    echo <<< HTML
        <option value="{$id}">$name</option>
        HTML;
}


?>
<form>
  <label for="selection">選択肢:</label>
  <select id="selection" name="selection">
    <option value="">--選択してください--</option>
    <option value="option1">選択肢1</option>
    <option value="option2">選択肢2</option>
    <option value="other">その他</option>
  </select>

  <div id="other-input-container" style="display: none;">
    <label for="other-input">その他（入力）:</label>
    <input type="text" id="other-input" name="other-input">
  </div>
</form>
<script>
    const selectElement = document.getElementById('selection');
    const otherInputContainer = document.getElementById('other-input-container');
    const otherInputElement = document.getElementById('other-input');

    selectElement.addEventListener('change', (event) => {
    if (event.target.value === 'other') {
        otherInputContainer.style.display = 'block'; // 入力欄を表示
        otherInputElement.required = true; // 必須化
    } else {
        otherInputContainer.style.display = 'none'; // 入力欄を非表示
        otherInputElement.required = false; // 必須を解除
        otherInputElement.value = ''; // 値をクリア
    }
    });

</script>
