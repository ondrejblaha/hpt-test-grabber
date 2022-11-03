<?php

declare(strict_types=1);

namespace HPT;

class Product {

    private string $code;
    private ?float $price;
    private ?string $name;
    private ?float $ratting;

    /**
     * @param string $code
     * @param float|null $price
     * @param string|null $name
     * @param float|null $ratting
     */
    public function __construct(string $code, ?float $price = null, ?string $name = null, ?float $ratting = null) {
        $this->code = $code;
        $this->price = $price;
        $this->name = $name;
        $this->ratting = $ratting;
    }

    /**
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @return float|null
     */
    public function getRatting(): ?float {
        return $this->ratting;
    }


}
