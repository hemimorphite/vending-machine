<?php
require_once 'VendingMachine.php';

abstract class State
{
    protected VendingMachine $vendingMachine;

    protected function __construct(VendingMachine $vendingMachine)
    {
        $this->vendingMachine = $vendingMachine;
    }

    abstract public function insertMoney($amount);
    abstract public function selectProduct($productCode);
    abstract public function dispenseProduct(); 
    abstract public function refill(array $products); 
    abstract public function cancel();
}
