<?php
if(!empty($_SESSION['product'])){

    echo <<< HTML
        <table>
            <tr>
                <th>商品番号</th><th>商品名</th><th>価額</th>
                <th>個数</th><th>小計<th></th></th>
            </tr>
    HTML;
    $total =0;
    foreach($_SESSION['product'] as $id=>$product){

        $price_format = number_format($product['price']);

        echo <<< HTML
            <tr>
                <td>{$id}</td>
                <td><a href="detail.php?id={$id}">{$product['name']}</a></td>
                <td>￥{$price_format}</td>
                <td>{$product['count']}</td>
                
        HTML;
        $subtotal = $product['price']*$product['count'];
        $subtotal_format = number_format($subtotal);
        $total += $subtotal;
        echo <<< HTML
            <td>￥{$subtotal_format}</td>
            <td><a href="cart-delete.php?id={$id}">削除</a></td>
            </tr>
        HTML;
    }

    $total_format = number_format($total);
    echo <<< HTML
        <tr><td>合計</td><td></td><td></td><td></td><td>￥{$total_format}</td><td></td></tr>

        </table>
    HTML;
}else{
    echo "商品がありません。";
}


?>

