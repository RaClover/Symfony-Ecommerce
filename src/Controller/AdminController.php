<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
//
    #[Route('/admin/dashboard' , name: 'admin_dashboard')]
    public function dashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }

}