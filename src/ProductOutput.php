<?php

declare(strict_types=1);

namespace HPT;

use HPT\Product;

class ProductOutput implements Output {

    private array $outputList;

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

    public function addProduct(Product $product): void {
        $this->productList[$product->getCode()] = $product;
    }

}
