<?php

declare(strict_types=1);

namespace HPT;

interface Grabber
{
    /**
     * @param string $productCode
     * @return Product|null
     */
    public function findProduct(string $productCode): ?Product;
}