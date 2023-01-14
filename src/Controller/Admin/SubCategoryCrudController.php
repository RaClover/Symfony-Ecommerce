<?php

namespace App\Controller\Admin;

use App\Entity\SubCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SubCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnIndex(),
            ImageField::new('image')
                ->hideOnForm(),
            TextField::new('name'),
            CollectionField::new('products')
            ->onlyOnIndex(),
            AssociationField::new('category'),
            ImageField::new('image')
                ->setBasePath('images/products')
                ->setUploadDir('public/images/products')
                ->setUploadedFileNamePattern('/images/products/[slug]-[timestamp].[extension]')
                ->hideOnIndex()
                ->hideOnDetail(),

        ];
    }
}
