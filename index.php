<?php

//decode the json into an object
$data = file_get_contents("./sales_data_big.json");
$json_data = json_decode($data);

//initializing total vars and array for products
$vat_total = 0;
$discount_total = 0;
$discount_with_vat_total = 0;
$products = array();

foreach($json_data as $datapoint){
    echo "Order id: {$datapoint->order->id}, by: {$datapoint->order->customer_name} <br>";
    foreach($datapoint->lines as $line){
        $vat = $line->unit_price_without_vat * ($line->vat_percentage/100);
        $vat_total += $vat * $line->qty;
        $vat_price = $line->unit_price_without_vat * (1 + $line->vat_percentage/100);

        $discount = $line->unit_price_without_vat * ($line->discount_percentage/100);
        $discount_added_vat = $discount * (1 + $line->vat_percentage/100);

        $discount_total += $discount;
        $discount_with_vat_total += $discount_added_vat;

        array_push($products, $line->product);

        echo "{$line->qty} x {$line->product} - VAT free price: {$line->unit_price_without_vat}, VAT included: {$vat_price}, VAT: {$line->vat_percentage}% <br>";
    }
}


$size = sizeof(array_unique($products));

echo "Total VAT to be paid: {$vat_total} € <br>";
echo "Total discount: {$discount_total} € (VAT 0%), {$discount_with_vat_total} € (VAT included) <br>";
echo "Total amount of unique products: {$size}";



?>