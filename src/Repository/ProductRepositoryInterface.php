<?php

namespace App\Repository;

use App\Document\Product;

interface ProductRepositoryInterface
{
    public function findAllSortedBySalesRank(): array;

    public function findByAsin(string $asin): ?Product;

    public function save(Product $product): void;

    public function delete(Product $product): void;
}
