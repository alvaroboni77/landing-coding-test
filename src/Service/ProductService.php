<?php

namespace App\Service;

use App\Document\Product;
use App\Repository\ProductRepositoryInterface;

class ProductService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function getSortedProducts(): array
    {
        return $this->productRepository->findAllSortedBySalesRank();
    }

    public function productExists(string $asin): bool
    {
        return $this->productRepository->findByAsin($asin) !== null;
    }

    public function saveProduct(Product $product): void
    {
        $this->productRepository->save($product);
    }

    public function deleteProduct(Product $product): void
    {
        $this->productRepository->delete($product);
    }
}