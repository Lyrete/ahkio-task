<?php
include_once 'product.php';

class Order{
    public $products;
    public int $id;
    public string $customer_name;
    public string $lang;
    public string $country;
    public float $vat = 0;
    public float $discount = 0;
    public float $discount_vat = 0;
    public $unique_products;

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

    function __construct($json){
        $this->id = $json->order->id;
        $this->customer_name = $json->order->customer_name;
        $this->lang = $json->order->language;
        $this->country = $json->order->country;
        $this->products = $json->lines;
        $this->unique_products = array();

        foreach($this->products as $x){
            $product = new Product($x);
            $this->vat += $product->getVatTotal();
            $this->discount += $product->getDiscountTotal();
            $this->discount_vat += $product->getDiscountWithVat();
            $this->unique_products[] = $product->name;
        }
    }
}