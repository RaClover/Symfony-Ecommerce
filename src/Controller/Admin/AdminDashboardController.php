<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\ProductColor;
use App\Entity\Products;
use App\Entity\ProductSize;
use App\Entity\SubCategory;
use App\Entity\User;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use phpDocumentor\Reflection\Types\This;
use PhpParser\Node\Expr\Yield_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminDashboardController extends AbstractDashboardController
{

    private UserRepository $users;
    private OrdersRepository $orders;
    private ProductsRepository $products;


    public function __construct(UserRepository $users , OrdersRepository $orders , ProductsRepository $products)
    {
        $this->users = $users;
        $this->orders = $orders;
        $this->products = $products;
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProductsCrudController::class)->generateUrl();



        return $this->redirect($url);



        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cool Fashion');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new \Exception('Wrong user');
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl($user->getImage())
            ->setMenuItems([
//                MenuItem::linkToUrl('My Account' , 'fa fa-user' , $this->generateUrl('app_user_profile_display')),
                MenuItem::linkToUrl('Logout' , 'fa-solid fa-right-from-bracket' , $this->generateUrl('app_logout'))
            ])
        ->displayUserName(false);
    }

    public function configureMenuItems(): iterable
    {
        $usersNum = count($this->users->findAll());
        $ordersNum = count($this->orders->findAll());
        $productsNum = count($this->products->findAll());
        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-gauge-high');
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-tag', Category::class);
        yield MenuItem::linkToCrud('SubCategories', 'fa-sharp fa-solid fa-tags', SubCategory::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-shirt', Products::class)
        ->setBadge($productsNum , 'success');
        yield MenuItem::linkToCrud('ProductsSizes', 'fa fa-layer-group', ProductSize::class);
        yield MenuItem::linkToCrud('ProductColors', 'fa fa-palette', ProductColor::class);
        yield MenuItem::linkToCrud('Orders', 'fa-solid fa-cart-shopping', Orders::class)
        ->setBadge($ordersNum  ,'success' );
        yield MenuItem::linkToCrud('OrderDetails', 'fa-sharp fa-solid fa-rectangle-list', OrderDetails::class);
        yield MenuItem::linkToCrud('Users', 'fa-solid fa-users', User::class)
        ->setBadge($usersNum, 'success');
        yield MenuItem::linkToUrl('HomePage', 'fa fa-home',$this->generateUrl('app_homepage'));

    }


    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX , Action::DETAIL);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin');
    }


}
