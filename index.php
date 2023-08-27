<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'app/Product.php';

$products = Product::all();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Vending Machine</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <style>
            button.btn-outline-primary:hover {
                background-color: transparent;
            }
            button.btn-outline-primary:focus,
            button.btn-outline-primary:focus-visible {
                opacity: 0.3;
            }
            
        </style>
    </head>
    <body>
        <main class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div id="products" class="row">
                        <?php foreach($products as $product): ?>
                        <div class="col-12 col-md-4 mb-4">
                            <div class="card" style="position:relative;">
                                <button type="button" class="btn btn-outline-primary" data-code="<?= $product->getId() ?>" data-price="<?= $product->getPrice() ?>" onclick="selectProduct(this)" style="position:absolute;width:100%;height:100%;"></button> 
                                <img src="images/<?= $product->getImage() ?>" class="card-img-top">
                                <div class="card-body">
                                    <div style="font-size:1.4rem;font-weight:bold;text-align:center;"><?= $product->getId() ?></div>
                                    <div style="font-size:1.4rem;font-weight:bold;text-align:center;"><?= $product->getprice() ?></div> 
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="refill()">REFILL</button>
                </div>
                <div class="col-12 col-md-6">
                    <div id="input"></div>
                    
                    <p style="font-size:1.2rem;font-weight:bold;">Screen</p>
                    <div id="screen" class="mb-5"></div>

                    <p style="font-size:1.2rem;font-weight:bold;">Paper Money Slot</p>
                    <div id="slot" class="row mb-5">
                        <div class="col-6 col-md-3 mb-2">
                            <div style="position:relative;">
                                <button type="button" class="btn btn-outline-primary" data-amount="2000" onclick="pay(this)" style="position:absolute;width:100%;height:100%;"></button>
                                <img src="images/2000.jpg" style="width:100%;">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div style="position:relative;">
                                <button type="button" class="btn btn-outline-primary" data-amount="5000" onclick="pay(this)" style="position:absolute;width:100%;height:100%;"></button>
                                <img src="images/5000.jpg" style="width:100%;">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div style="position:relative;">
                                <button type="button" class="btn btn-outline-primary" data-amount="10000" onclick="pay(this)" style="position:absolute;width:100%;height:100%;"></button>
                                <img src="images/10000.jpg" style="width:100%;">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div style="position:relative;">
                                <button type="button" class="btn btn-outline-primary" data-amount="20000" onclick="pay(this)" style="position:absolute;width:100%;height:100%;"></button>
                                <img src="images/20000.jpg" style="width:100%;">
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div style="position:relative;">
                                <button type="button" class="btn btn-outline-primary" data-amount="50000" onclick="pay(this)" style="position:absolute;width:100%;height:100%;"></button>
                                <img src="images/50000.jpg" style="width:100%;">
                            </div>
                        </div>
                    </div>

                    <p style="font-size:1.2rem;font-weight:bold;">Change</p>
                    <div id="change" class="row mb-5"></div>
                </div>
            </div> 
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script>
            function ajax(method, url, callback, data) {
                let xhr = new XMLHttpRequest();
                xhr.open(method, url, true);
                xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        callback(xhr.responseText);
                    }
                };

                xhr.send(data);
            }

            ajax('GET', 'post.php', function(res) {
                document.getElementById('screen').innerHTML = res;
            });

            function refill() {
                let data = `refill=1`;
                ajax('POST', 'post.php', function(res) {
                    document.getElementById('screen').innerHTML = res;
                }, data); 
            }

            function selectProduct(element) {
                let input = document.getElementById('input');
                let change = document.getElementById('change');

                if(input.hasAttribute("data-amount")) {
                    return;
                }

                let id = element.dataset.code;
                let price = element.dataset.price; 
                input.dataset.id = id;
                input.dataset.price = price;
                change.innerHTML = '';
                let data = `id=${id}`;

                ajax('POST', 'post.php', function(res) {
                    document.getElementById('screen').innerHTML = res;
                }, data);
            }

            function pay(element) {
                let input = document.getElementById('input');
                let amount = element.dataset.amount;

                if(input.hasAttribute("data-amount")) {
                    amount = input.dataset.amount + ';' + element.dataset.amount;
                }

                input.dataset.amount = amount;

                let data = `amount=${amount}`;

                if(input.hasAttribute("data-id")) {
                    let id = input.dataset.id;
                    data = `id=${id}&amount=${amount}`;
                }

                ajax('POST', 'post.php', function(res) {
                    document.getElementById('screen').innerHTML = res;

                    let input = document.getElementById('input');

                    if(!input.hasAttribute("data-id")) {
                        input.removeAttribute('data-amount');
                    } else {
                        let price = parseInt(input.dataset.price);
                        let amounts = document.getElementById('input').dataset.amount.split(';');
                        let pay = 0; 
                        amounts.forEach((amount) => { pay += parseInt(amount); });
                        let change = pay - price;
                        let denominations = [100000, 50000, 20000, 10000, 5000, 2000, 1000];
                        let changeDenominations = [];
                        let index = 0;
                        
                        if(change > 0) {
                            denominations.forEach(function(denomination) {
                                while(denomination <= change) {
                                change -= denomination;
                                changeDenominations[index] = denomination;
                                index++;
                                }
                            });

                            let changeBox = document.getElementById('change');

                            changeDenominations.forEach(function(change) { 
                                const str = '<div class="col-6 col-md-3 mb-2">' +
                                                '<img src="images/'+change+'.jpg" style="width:100%;">'+
                                            '</div>';

                                changeBox.insertAdjacentHTML( 'beforeend', str );
                            });

                            input.removeAttribute('data-id');
                            input.removeAttribute('data-price');
                            input.removeAttribute('data-amount');

                            setTimeout(function(){
                                changeBox.innerHTML = '';  
                            }, 60000); 
                        }      
                    }
                }, data);
            }
        </script>
    </body>
</html>

