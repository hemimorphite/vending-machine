<?php
require_once 'State.php';
require_once 'IdleState.php';
require_once 'SoldOutState.php';

class DispenseProductState extends State
{
    public function __construct($vendingMachine)
    {
        parent::__construct($vendingMachine);
        echo "DISPENSE<br>";
    }

    public function cancel()
    {
        echo "Cannot cancel dispensing operation.<br>";
    }

    public function dispenseProduct()
    {
        if($this->vendingMachine->getSelectedProductCode() == null)
        {
            echo "There is no selected product to dispense.<br>";
            $this->vendingMachine->setState(new IdleState($this->vendingMachine));
            return;
        }

        echo "Dispensing product.<br>";
        $selectedProduct;

        foreach($this->vendingMachine->products as $product){
            if($product->getId() == $this->vendingMachine->getSelectedProductCode())
            {
                $selectedProduct = $product;
                break;
            }
        }
        $selectedProduct->setStock($product->getStock() - 1);
        $selectedProduct->save(); 
        $this->vendingMachine->setSelectedProductCode(null);
        echo "Product dispensed.<br>";

        $soldOut = true;
        foreach($this->vendingMachine->products as $product){
            if($product->getStock() <> 0)
            {
                $soldOut = false;
                break;
            }
        }
        if ($soldOut)
        {
            $this->vendingMachine->setState(new SoldOutState($this->vendingMachine));
        }
        else
        {
            $this->vendingMachine->setState(new IdleState($this->vendingMachine));
        }
    }

    public function insertMoney($money)
    {
        echo "Cannot insert money during product dispensing.<br>";
    }

    public function refill(array $products)
    {
        echo "Cannot refill during dispensing product.<br>";
    }
     
    public function selectProduct($productCode)
    {
        echo "Product already selected.<br>";
    }
}
