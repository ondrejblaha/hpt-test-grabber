<?php

declare(strict_types=1);

namespace HPT;

use HPT\Product;

interface Grabber
{
    public function findProduct(string $productCode): ?Product;
}