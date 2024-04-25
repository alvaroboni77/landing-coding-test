<?php

namespace App\Repository;

use App\Document\Product;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class ProductRepository extends ServiceDocumentRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllSortedBySalesRank(): array
    {
        return $this->createQueryBuilder()
            ->sort('salesRank', 'ASC')
            ->getQuery()
            ->execute()
            ->toArray();
    }

    public function findByAsin(string $asin): ?Product
    {
        return $this->findOneBy(['asin' => $asin]);
    }

    public function save(Product $product): void
    {
        $this->getDocumentManager()->persist($product);
        $this->getDocumentManager()->flush();
    }

    public function delete(Product $product): void
    {
        $this->getDocumentManager()->remove($product);
        $this->getDocumentManager()->flush();
    }
}
