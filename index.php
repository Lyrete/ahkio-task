<html>
    <head>
        <!-- get Darkly stylesheet (dark themed bootstrap) so my eyes don't burn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/darkly/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
</body>
</html>

<?php

include_once 'order.php';

//decode the json into an object
$data = file_get_contents("./sales_data.json");
$json_data = json_decode($data);

//init vars for echo usage
$vat_total = 0;
$discount_total = 0;
$discount_with_vat_total = 0;
$products = array();
$product_amount = 0;

//loop through orders and add values to totals
foreach($json_data as $datapoint){
    $order = new Order($datapoint);
    $vat_total += $order->vat;
    $discount_total += $order->discount;
    $discount_with_vat_total += $order->discount_vat;
    $product_amount += $order->product_amount;

    //merge existing product array with the products in the order
    $products = array_merge($products, $order->unique_products);

}

//remove duplicates from the product array and get it's size
$size = sizeof(array_unique($products));

//echo a table for visuals
echo "<table class='table'><tbody>";
echo "<tr><td>Total VAT to be paid</td><td> {$vat_total} €</td> </tr>";
echo "<tr><td>Total discount given</td><td> {$discount_total} € (VAT 0%), {$discount_with_vat_total} € (VAT included)</td>  </tr>";
echo "<tr><td>Total amount of unique products in orders</td><td> {$size}</td> </tr>";
echo "<tr><td>Total amount of ordered products</td><td> {$product_amount}</td> </tr>";
echo "</tbody></table>";

?>


