<?php

class Product
{
    protected $id;

    protected $name;

    protected $price;

    protected $stock;

    protected $image;

    public function __construct($id, $name, $price, $stock, $image) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->image = $image;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getImage()
    {
        return $this->image;
    }
   
    public function save()
    {
        $file = fopen(__DIR__."/products.txt", "r") or die("Unable to open file!");
        $str = fread($file, filesize(__DIR__."/products.txt"));
        $lines = array_filter(explode(PHP_EOL, $str));
        fclose($file);

        $file = fopen(__DIR__."/products.txt", "w") or die("Unable to open file!");
        
        foreach ($lines as $line) {
            $attr = array_filter(explode(';', $line));
            if($attr[0] == $this->id) {
                $line = $attr[0].';'.$attr[1].';'.$attr[2].';'.$this->stock.';'.$this->image.PHP_EOL;
            } else {
                $line = $line.PHP_EOL;
            }
            fwrite($file, $line);
        }
        fclose($file);
    }

    public static function all()
    {
        $file = fopen(__DIR__."/products.txt", "r") or die("Unable to open file!");
        $str = fread($file, filesize(__DIR__."/products.txt"));
        $lines = array_filter(explode(PHP_EOL, $str));
        fclose($file);

        $products = [];

        foreach ($lines as $line) {
            $attr = array_filter(explode(';', $line));
            
            if(!isset($attr[3]))
            {
                $attr[3] = "0";
            }
            $product = new self($attr[0],$attr[1],$attr[2],$attr[3],$attr[4]);

            $products[] = $product; 
        }

        return $products;
    }
}
