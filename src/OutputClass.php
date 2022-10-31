<?php

declare(strict_types=1);

namespace HPT;

class OutputClass implements Output {

    public array $outputList;

    public function __construct() {
        $this->outputList = array();
    }

    public function getJson(): string {

        return json_encode($this->outputList);
    }

    public function addProductData(string $code, float $price = 0, float $ratting = 0, string $name = ''): void {

        if($price == 0) {
            $this->outputList[$code] = null;
        } else {
            $this->outputList[$code] = array('price' => $price, 'ratting' => $ratting, 'name' => $name);
        }
    }

}
