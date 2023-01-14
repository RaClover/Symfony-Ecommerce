<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use App\Repository\SubCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{


    #[Route('/', name: 'redirect-to-home')]
    public function redirectToHome():Response
    {
        return $this->redirectToRoute('app_homepage');
    }

    #[Route('/home', name: 'app_homepage')]
    public function home(ProductsRepository $productsRepository): Response
    {


        $user = $this->getUser();
        return $this->render('app/home.html.twig' , [
            'user' => $user,
            'products' =>   $productsRepository->findBy([] , ['id' => 'ASC'] , 3)
        ]);
    }


    #[Route('/shop' , name: 'app_shop_page')]
    public function Shop(
        ProductsRepository $productsRepository ,
        Request $request ,
        PaginatorInterface $paginator,
        CategoryRepository $categoryRepository,
    ):Response
    {
        $products = $productsRepository->findBy([] , ['id' => 'ASC']);

        $allProducts = $paginator->paginate(
            $products,
            $request->query->getInt('page' , 1),
            12
        );



        $categories = $categoryRepository->findAll();
        return $this->render('app/shop.html.twig' , [
            'products' => $allProducts,
            'categories' => $categories
        ]);
    }



    #[Route('/sub/{id}' , name: 'shop_subCategory')]
    public function shop_subCategory(
        $id,
        CategoryRepository $categoryRepository,
        SubCategoryRepository $subCategoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ):Response
    {
        $categories = $categoryRepository->findAll();
        $subCategory = $subCategoryRepository->findOneBy(['id' => $id]);

        $products = $subCategory->getProducts();

        $allProducts = $paginator->paginate(
            $products,
            $request->query->getInt('page' , 1),
            12
        );


        return $this->render('app/shop.html.twig' , [
            'categories' => $categories,
            'products' => $allProducts
        ]);
    }





    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact' , name: 'app_contact_page')]
    public function contact(Request $request , MailerInterface $mailer):Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        $transport = Transport::fromDsn('smtp://Raheeb772652540@gmail.com:aaxpnumgjaemtvyg@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();


            $email = (new TemplatedEmail())
                ->from(new Address($this->getUser()->getUserIdentifier() , $contactFormData['name']))
                ->to('Raheeb772652540@gmail.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject($contactFormData['subject'])
                ->htmlTemplate('messages/contactMsg.html.twig')
                ;
            $mailer->send($email);

        }
        return $this->render('app/contact.html.twig' , [
            'contactForm' => $form->createView(),
        ]);
    }


    
}
