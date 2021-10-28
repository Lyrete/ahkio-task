<?php

class Product{
    public string $name;
    public int $id;
    public int $unit_price_without_vat;
    public int $discount_percentage = 0;
    public int $qty;
    public int $vat_percentage;


    /*
    *   {
    *       id: int,
    *       vat_percentage: int,
    *       qty: int,
    *       unit_price_without_vat: int,
    *       product: string,
    *       (discount_percentage: int)
    *   }
    */
    function __construct($json){
        $this->name = $json->product;
        $this->qty = $json->qty;
        $this->unit_price_without_vat = $json->unit_price_without_vat;
        $this->vat_percentage = $json->vat_percentage;
        $this->id = $json->id;
        if(isset($json->discount_percentage)){
            $this->discount_percentage = $json->discount_percentage;
        }
    }

    //vat for one unit
    function getVat(){ 
        return $this->vat_percentage/100 * $this->unit_price_without_vat; 
    }

    //vat for all units
    function getVatTotal(){
        return $this->getVat() * $this->qty;
    }

    //discount for one unit
    function getDiscount(){
        return ($this->unit_price_without_vat + $this-> getVat()) * $this->discount_percentage / 100;
    }

    //vat free discount for all units
    function getVatFreeDiscount(){
        return $this->unit_price_without_vat * $this->discount_percentage / 100 * $this->qty;
    }

    //discount for all units
    function getDiscountTotal(){
        return $this->getDiscount() * $this->qty;
    }
}

?>