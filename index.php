<html>
    <head>
        <!-- get Darkly stylesheet (dark themed bootstrap) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/darkly/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
</body>
</html>

<?php

//decode the json into an object
$data = file_get_contents("./sales_data.json");
$json_data = json_decode($data);

/*
* json is an array of orders with the following structure
*
* order:
*   {
*       id: int,
*       language: string,
*       customer_name: string,
*       country: string
*   },
* lines: 
*   [...,{
*       id: int,
*       vat_percentage: int,
*       qty: int,
*       unit_price_without_vat: int,
*       product: string,
*       (discount_percentage: int)
*   },...]
*
* with the lines array being any length
*/

//initializing sum vars and array of counted products (to be used for uniques)
$vat_total = 0;
$discount_total = 0;
$discount_with_vat_total = 0;
$products = array();

//loop through orders
foreach($json_data as $datapoint){
    
    //loop through the lines array of an order
    foreach($datapoint->lines as $line){

        //calculate vat paid for one product
        $vat = $line->unit_price_without_vat * ($line->vat_percentage/100);
        
        //add to total sum (times quantity of product)
        $vat_total += $vat * $line->qty;

        //calculate discount without and with vat
        $discount = $line->unit_price_without_vat * ($line->discount_percentage/100);
        $discount_added_vat = $discount * (1 + $line->vat_percentage/100);

        //add to discount totals (quantity taken into account)
        $discount_total += $discount * $line->qty;
        $discount_with_vat_total += $discount_added_vat * $line->qty;

        //add product name to array of products
        array_push($products, $line->product);
    }
}

//remove duplicates from array and get it's size
$size = sizeof(array_unique($products));

//echo a table for visuals
echo "<table class='table'><tbody>";
echo "<tr><td>Total VAT to be paid</td><td> {$vat_total} €</td> </tr>";
echo "<tr><td>Total discount given</td><td> {$discount_total} € (VAT 0%), {$discount_with_vat_total} € (VAT included)</td>  </tr>";
echo "<tr><td>Total amount of unique products</td><td> {$size}</td> </tr>";
echo "</tbody></table>";

?>


