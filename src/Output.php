<?php

declare(strict_types=1);

namespace HPT;

interface OutputInterface
{
    public function getJson(): string;
}

class Output implements OutputInterface {

    public array $outputList;

    public function __construct() {
        $this->outputList = array();
    }

    public function getJson(): string {

        return json_encode($this->outputList);
    }

    public function addProductData($code, $price = 0) {

        if($price == 0) {
            $this->outputList[$code] = null;
        } else {
            $this->outputList[$code] = array('price' => $price);
        }
    }

}
