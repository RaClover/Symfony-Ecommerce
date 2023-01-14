<?php

namespace App\Service;

use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AppService
{


    private $productsRepository;
    private $em;
    private $paginator;


    public function __construct(ProductsRepository $productsRepository , EntityManagerInterface $em , Paginator $paginator)
    {
        $this->em = $em;
        $this->productsRepository = $productsRepository;
        $this->paginator = $paginator;
    }





}