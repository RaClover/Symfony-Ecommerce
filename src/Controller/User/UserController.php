<?php

namespace App\Controller\User;

use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Repository\OrderDetailsRepository;
use App\Repository\OrdersRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

#[Route('/user/profile', name: 'app_user_profile_')]
class UserController extends AbstractController
{



    #[Route('/display', name: 'display')]
    public function displayProfile(): Response
    {
        return $this->render('user/profile.html.twig', [
        ]);
    }



    #[Route('/info'  , name: 'info')]
    public function profileHome():Response
    {
        return $this->render('user/profile.html.twig');
    }



    #[Route('/editPage' , name: 'displayEditPage')]
    public function displayEditPage():Response
    {
        return $this->render('user/profileEdit.html.twig');
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function editProfile($id , Request $request, UserRepository $userRepository): Response
    {

        $files = $request->files;

        $user = $userRepository->findOneBy(['id' => $id]);
        $firstName = $request->get('firstname');
        $lastName = $request->get('lastname');
        $email =  $request->get('email');
        $country = $request->get('country');
        $city = $request->get('city');
        $streetAddress  = $request->get('streetAddress');
        $password = $request->get('password');
        $image = $files->get('image');

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setCountry($country);
        $user->setCity($city);
        $user->setStAddress($streetAddress);
        $user->setPassword($password);

        if ($image){
              $newFileName = uniqid() . '.' . $image->guessExtension();

              try {
                  $image->move(
                      $this->getParameter('kernel.project_dir') . '/public/images/user' ,
                      $newFileName
                  );
              }catch (FileException $e) {
                  return new Response($e->getMessage());
              }

              $user->setImage('/images/user/' . $newFileName);
          }
        $userRepository->save($user , true);



        return $this->redirectToRoute('app_user_profile_info');
    }



    #[Route('/ordersHistory', name: 'ordersHistory')]
    public function ordersHistory(
        OrderDetailsRepository $orderDetailsRepository ,
        OrdersRepository $ordersRepository ,
        UserRepository $userRepository
    )
    {
        $user = $this->getUser();
        $order = $ordersRepository->findOneBy(['user' => $user]);
        $orderDetail = $orderDetailsRepository->findOneBy(['theOrder' =>$order ]);

//        dd($orderDetail);

        return $this->render('user/history.html.twig' , [
            'user' => $user,
            'orderDetails' => $orderDetail,
//            'order' => $order
        ]);
    }



    #[Route('/view/{id}' , name: 'viewUserOrder')]
    public function viewUserOrder(Orders $orders ,
                                  OrderDetailsRepository $orderDetailsRepository,
                                  OrdersRepository $ordersRepository,
                                   UserRepository $userRepository
    ):Response
    {
        $id = $orders->getId();

        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $order = $ordersRepository->findOneBy(['id' => $id]);
        $theOrder =  $orderDetailsRepository->findOneBy(['theOrder' => $order]);

        return $this->render('user/orderView.html.twig' ,[
            'order' => $theOrder,
            'user' => $user
        ]);
    }



}
