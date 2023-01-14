<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/prod/{id}' , name: 'product_detail')]
    public function product_detail
    (
        $id ,
        ProductsRepository $productsRepository
    ): Response
    {

        $product = $productsRepository->findOneBy(['id'  => $id]);

        return $this->render('product/detail.html.twig' , [
            'product' => $product
        ]);
    }
}
