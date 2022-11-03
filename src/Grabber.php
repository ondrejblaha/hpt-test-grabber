<?php

declare(strict_types=1);

namespace HPT;

interface Grabber
{
    public function findProduct(string $productCode): ?Product;
}