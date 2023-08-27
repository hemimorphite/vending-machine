<?php
require_once 'app/Product.php';
require_once 'app/VendingMachine.php';

$vendingMachine = new VendingMachine(Product::all());

if(isset($_POST['id']))
{
    $vendingMachine->selectProduct($_POST['id']);
}

if(isset($_POST['amount']))
{
    $amounts = array_filter(explode(';', $_POST['amount']));
   
    foreach($amounts as $amount) 
    {
        $vendingMachine->insertMoney((int)$amount);
    } 
}

if(isset($_POST['refill']))
{
    $vendingMachine->refill(Product::all());
}
?>
