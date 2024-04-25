<?php

namespace App\Controller;

use App\Document\Product;
use App\Exception\EmptyProductListException;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LandingController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index():Response
    {
        try {
            $products = $this->productService->getSortedProducts();
            if (empty($products)) {
                throw new EmptyProductListException();
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
}