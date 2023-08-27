<?php
require_once 'State.php';
require_once 'IdleState.php';
require_once 'DispenseProductState.php';

class PaymentState extends State
{
    private $funds = 0;
    private $denominations = [2000, 5000, 10000, 20000, 50000];

    public function __construct($vendingMachine)
    {
        parent::__construct($vendingMachine);    
        echo "PAYMENT - You can only pay with denominations of 2000, 5000, 10000, 20000, or 50000.<br>";
    }

    public function cancel()
    {
        echo "Cancelling order.<br>";

        if($this->funds > 0) 
        {
            echo "Returning the amount of ".$this->funds."<br>";
        }
        $this->vendingMachine->setSelectedProductCode(null);
        $this->vendingMachine->setState(new IdleState($this->vendingMachine));
    }

    public function dispenseProduct()
    {
        echo "Cannot dispense product yet. Insuffiecient funds.<br>";
    }

    public function insertMoney($money)
    {
        if (!in_array($money, $this->denominations))
        {
            echo "Pay with denominations of 2000, 5000, 10000, 20000, or 50000.<br>";
            return;
        }
 
        $this->funds += $money;
        $selectedProduct;

        foreach($this->vendingMachine->products as $product){
            if($product->getId() == $this->vendingMachine->getSelectedProductCode())
            {
                $selectedProduct = $product;
                break;
            }
        }
        
        if($this->funds < $selectedProduct->getPrice())
        {
            $remain = $selectedProduct->getPrice() - $this->funds;
            echo "Remaining: $remain<br>";
        } 
        else
        {
            echo "Proper amount received.<br>";
            $change = $this->funds - $selectedProduct->getPrice();
         
            if ($change > 0)
            {
                echo "Dispensing $change amount.<br>";
            }
            $this->vendingMachine->setState(new DispenseProductState($this->vendingMachine));
            $this->vendingMachine->dispenseProduct();
        }
    }

    public function refill(array $products)
    {
        echo "Cannot refill during payment operation. Please cancel or complete payment before refill.<br>";
    }
    public function selectProduct($productCode)
    {
        echo "Product is already selected. Please complete or cancel the current payment.<br>";
    }
}
