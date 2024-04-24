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
        $products = $this->documentManager->getRepository(Product::class)->createQueryBuilder()
            ->sort('salesRank', 'ASC')
            ->getQuery()->execute();

        return $this->render('landing/index.html.twig', [
            'products' => $products
        ]);
    }
}