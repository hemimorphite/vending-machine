<?php
require 'State.php';
require 'IdleState.php';

class VendingMachine
{
    public array $products;
    public State $currentState;
    private $productCode;

    public function __construct(array $products)
    {
        $this->products = $products;
        $this->currentState = new IdleState($this); 
    }

    public function setSelectedProductCode($productCode)
    {
        $this->productCode = $productCode;
    }

    public function getSelectedProductCode()
    {
        return $this->productCode;
    }

    public function selectProduct($productCode)
    {
        $this->currentState->selectProduct($productCode);
    }

    public function insertMoney($amount)
    {
        $this->currentState->insertMoney($amount);
    }

    public function dispenseProduct()
    {
        $this->currentState->dispenseProduct();
    }

    public function refill(array $products)
    {
        $this->currentState->refill($products);
    }

    public function setState(State $state)
    {
        $this->currentState = $state;
    }
}
