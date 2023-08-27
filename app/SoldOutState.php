<?php
require_once 'State.php';

class SoldOutState extends State
{
    public function __construct(VendingMachine $vendingMachine)
    {
        parent::__construct($vendingMachine);
        echo "SOLDOUT<br>";
    }

    public function insertMoney($money)
    {
        echo "There are no products in the vending machine<br>";
    }
   
    public function selectProduct($productCode)
    {
        echo "There are no products in the vending machine<br>";
    }

    public function cancel()
    {
        echo "There is no operation to cancel.<br>";
    }

    public function dispenseProduct()
    { 
        echo "There is no selected product.<br>";
    } 

    public function refill(array $products)
    {
        $this->vendingMachine->products = $products;

        $stock = 0;
        foreach($this->vendingMachine->products as $product){
            $stock += $product->getStock();
        }
        echo "Total amount of products: $stock<br>";
    }
}
