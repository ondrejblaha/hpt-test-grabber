<?php

declare(strict_types=1);

namespace HPT;

interface Output
{
    public function getJson(): string;
    public function addProduct(Product $Product): void;
}
