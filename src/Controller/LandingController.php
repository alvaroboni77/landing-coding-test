<?php

namespace App\Controller;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LandingController extends AbstractController
{
    public function __construct(private DocumentManager $documentManager)
    {
    }

    public function index():Response
    {
        try {
            $products = $this->getSortedProducts();
            if (empty($products)) {
                throw new \Exception('No products found.');
            }
        } catch (\Exception $e) {
            return $this->render('landing/error.html.twig', [
                'message' => $e->getMessage()
            ]);
        }

        return $this->render('landing/index.html.twig', [
            'products' => $products
        ]);
    }

    private function getSortedProducts(): array
    {
        return $this->documentManager->getRepository(Product::class)
            ->createQueryBuilder()
            ->sort('salesRank', 'ASC')
            ->getQuery()
            ->execute()
            ->toArray();
    }
}