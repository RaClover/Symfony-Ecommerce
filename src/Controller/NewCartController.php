<?php

namespace App\Controller;

use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\User;
use App\Form\CheckoutFormType;
use App\Repository\OrderDetailsRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use App\Service\CartService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Template;


#[Route('/cart' , name: 'cart_')]
class NewCartController extends AbstractController
{



    #[Route('/' , name: 'index')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository):Response
    {
        $cart = $session->get("cart", []);


        // We "fabricate" the data
        $dataCart = [];
        $total = 0;

        foreach($cart as $id => $quantity){
            $product = $productsRepository->find($id);
            $dataCart[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        $session->set("cartData", $dataCart);

        return $this->render('new_cart/index.html.twig' , [
            'dataCart' => $dataCart,
            'total' => $total
        ]);

    }

    #[Route('/add/{id}' , name: 'add')]
    public function add(
        $id,
        SessionInterface $session ,
        OrdersRepository $ordersRepository ,
        OrderDetailsRepository $orderDetailsRepository,
        ProductsRepository $productsRepository,
    ):Response
    {
        // We get the current cart
        $cart = $session->get("cart", []);
        $product = $productsRepository->findOneBy(['id' => $id]);
        $id = $product->getId();

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $quantity = $cart[$id];
        $prodPrice = $product->getPrice();
        $price = $quantity * $prodPrice;

        // We save in the session
        $session->set("cart", $cart);





        // We "fabricate" the data
        $dataCart = [];
        $total = 0;

        foreach($cart as $id => $quantity){

            $product = $productsRepository->find($id);
            $dataCart[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        $session->set("cartData", $dataCart);


        return $this->redirectToRoute("app_shop_page");
    }


    #[Route('/remove/{id}' , name: 'remove')]
    public function remove($id, SessionInterface $session , ProductsRepository $productsRepository):Response
    {
        // We get the current basket
        $cart = $session->get("cart", []);
        $product = $productsRepository->findOneBy(['id' => $id]);
        $id = $product->getId();

        if(!empty($cart[$id])){
            if($cart[$id] > 1){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }

        // We save in the session
        $session->set("cart", $cart);

        return $this->redirectToRoute("cart_index");
    }

    #[Route('/delete/{id}' , name: 'delete')]
    public function delete($id, SessionInterface $session , ProductsRepository $productsRepository):Response
    {
        // We get the current basket
        $cart = $session->get("cart", []);
        $product = $productsRepository->findOneBy(['id' => $id]);
        $id = $product->getId();

        if(!empty($cart[$id])){
            unset($cart[$id]);
        }

        // We save in the session
        $session->set("cart", $cart);

        return $this->redirectToRoute("cart_index");
    }


    #[Route('/delete' , name: 'delete_all')]
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("cart");

        return $this->redirectToRoute("cart_index");
    }


    #[Route('/checkout' , name: 'checkout')]
    public function checkout(
        Request $request ,
        SessionInterface $session  ,
        ProductsRepository $productsRepository,
        OrdersRepository $ordersRepository,
        OrderDetailsRepository $orderDetailsRepository
    ):Response
    {

        if (!$request->getSession()->has('cart')) {
            return $this->redirectToRoute('app_homepage');
        }else {


            $form = $this->createForm(CheckoutFormType::class);
            $form->handleRequest($request);
            $checkoutData = $form->getData();
            $dataCart = [];
            $total = 0;
            $cart = $session->get("cart", []);
            $order = new Orders();
            $orderDetail = new OrderDetails();
            $product = new Products();
            // We "fabricate" the data
            foreach ($cart as $id => $quantity) {
                $product = $productsRepository->find($id);
                $user = $this->getUser();
                $dataCart[] = [
                    "product" => $product,
                    "quantity" => $quantity,
                    "user" => $user
                ];
                $total += $product->getPrice() * $quantity;

            }
            $orderNumber = mt_rand(111111111, 999999999);
            $amount = sizeof($dataCart);


            if ($form->isSubmitted()) {

                $order->setUser($this->getUser());
                $order->setOrderStatus('checkedout');
                $order->setUpdatedAt(new \DateTime());
                $order->setOrderDate(new \DateTime());
                $order->setOrderNumber($orderNumber);


                $orderDetail->setTheOrder($order);
                $orderDetail->setQuantity($amount);
                $orderDetail->setPrice($total);
                $orderDetail->setProducts($product);


                $ordersRepository->save($order, true);
                $orderDetailsRepository->save($orderDetail, true);
                $session->remove('cart');
                $this->addFlash('successOrder', 'Thank You for your order!. Please check your email Well email you an order confirmation with details and tracking info..');
            }
            return $this->render('new_cart/checkout.html.twig', [
                'dataCart' => $dataCart,
                'total' => $total,
                'formCheckout' => $form->createView()
            ]);
        }
    }






    #[Route('/checkoutSuccess' , name: 'checkout-Success')]
    public function checkoutSuccess(
        SessionInterface $session
    ):Response
    {
        $session->clear();
        return $this->render('new_cart/placeOrder.html.twig', [

        ]);

    }




}
