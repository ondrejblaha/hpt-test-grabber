<?php

declare(strict_types=1);

namespace HPT;

interface Output
{
    /**
     * @return string
     */
    public function getJson(): string;

    /**
     * @param Product $Product
     * @return void
     */
    public function addProduct(Product $Product): void;
}
