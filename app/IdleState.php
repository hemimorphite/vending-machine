<?php
require_once 'State.php';
require_once 'PaymentState.php';

class IdleState extends State
{
    public function __construct(VendingMachine $vendingMachine)
    {
        parent::__construct($vendingMachine);
        echo "IDLE - Wait for product selection<br>";
    }
    
    public function insertMoney($amount)
    {
        echo "Please select a product before inserting any money.<br>";
    }

    public function selectProduct($productCode)
    {
        $selectedProduct;
        
        foreach($this->vendingMachine->products as $product){
            if($product->getId() == $productCode)
            {
                $selectedProduct = $product;
                break;
            }  
        }

        if($selectedProduct->getStock() == 0)
        {
            echo "The product code:$productCode is out of stock.<br>";
            return;
        }
    
        $this->vendingMachine->setSelectedProductCode($selectedProduct->getId());
        echo "Product: ".$selectedProduct->getId()." with price: ".$selectedProduct->getPrice()." selected.<br>";
        $this->vendingMachine->setState(new PaymentState($this->vendingMachine));
    }
   
    public function dispenseProduct()
    {
        echo "Select a product first.<br>";
    }

    public function cancel()
    {
        echo "There is no selected product or payment in progress to cancel.<br>";
    }

    public function refill(array $products)
    {   
        $stock = 0;

        foreach($products as $product) {
            $product->setStock(5);
            $product->save();
            $stock += 5;
        }

        $this->vendingMachine->products = $products;

        //$stock = 0; 
        //foreach($this->vendingMachine->products as $product){
        //    $stock += $product->getStock();
        //}
        echo "Total amount of products: $stock<br>";
    }
}
