<?php

namespace App\Service;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;

class ProductService
{
    private DocumentManager $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function getSortedProducts(): array
    {
        return $this->documentManager->getRepository(Product::class)
            ->createQueryBuilder()
            ->sort('salesRank', 'ASC')
            ->getQuery()
            ->execute()
            ->toArray();
    }

    public function productExists(string $asin): bool
    {
        return $this->documentManager->getRepository(Product::class)->findOneBy(['asin' => $asin]) !== null;
    }

    public function saveProduct(Product $product): void
    {
        $this->documentManager->persist($product);
        $this->documentManager->flush();
    }

    public function deleteProduct(Product $product): void
    {
        $this->documentManager->remove($product);
        $this->documentManager->flush();
    }
}
