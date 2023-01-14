<?php

namespace App\Service;

use App\Entity\Products;
use App\Repository\OrderDetailsRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    private $session;
    private $ordersRepository;
    private $orderDetailsRepository;
    private $productsRepository;
    private $product;

    public function __construct(
        SessionInterface $session ,
        OrdersRepository $ordersRepository ,
        OrderDetailsRepository $orderDetailsRepository,
        ProductsRepository $productsRepository,
        Products $product
    )
    {
        $this->session = $session;
        $this->ordersRepository = $ordersRepository;
        $this->orderDetailsRepository = $orderDetailsRepository;
        $this->productsRepository = $productsRepository;
        $this->product = $product;
    }








}