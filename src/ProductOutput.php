<?php

declare(strict_types=1);

namespace HPT;

class ProductOutput implements Output {

    private array $productList;

    public function __construct() {
        $this->productList = array();
    }

    public function getJson(): string {

        $output = array();
        foreach($this->productList as $product) {
            
            $output[$product->getCode()] = $product->getPrice() !== null ? $productOutput = array(
                        'price' => $product->getPrice(),
                        'name' => $product->getName(),
                        'ratting' => $product->getRatting()) : null;
        }
        return json_encode($output);
    }

    public function addProduct(Product $Product): void {
        $this->productList[$Product->getCode()] = $Product;
    }

}
