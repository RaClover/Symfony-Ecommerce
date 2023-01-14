<?php

namespace App\Controller\Admin;

use App\Entity\ProductColor;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductColorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductColor::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnIndex(),
            TextField::new('name'),
        ];
    }
}
