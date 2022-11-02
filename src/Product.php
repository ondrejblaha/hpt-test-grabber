<?php

declare(strict_types=1);

namespace HPT;

class Product {

    private $code;
    private $price;
    private $name;
    private $ratting;

    public function __construct(string $code, ?float $price = null, ?string $name = null, ?float $ratting = null) {
        $this->code = $code;
        $this->price = $price;
        $this->name = $name;
        $this->ratting = $ratting;
    }
    public function getCode(): string {
        return $this->code;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getRatting(): ?float {
        return $this->ratting;
    }   

    
}
