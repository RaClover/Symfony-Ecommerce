<?php

namespace App\Controller\Admin;

use App\Entity\ProductColor;
use App\Entity\Products;
use App\Entity\ProductSize;
use Doctrine\DBAL\Types\FloatType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

//use Vich\UploaderBundle\VichUploaderBundle;
//use Vich\UploaderBundle\Form\Type\VichFileType;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            ImageField::new('image')
            ->hideOnForm(),
            TextField::new('name'),
            TextField::new('price'),
            TextareaField::new('description'),
            ArrayField::new('sizes'),
            ArrayField::new('colors'),

            AssociationField::new('subCategory'),
//            ImageField::new('imageFile' , "product image")
//            ->setFormType(VichImageType::class)
//                ->setBasePath('images/products')
//                ->setUploadDir('public/images/products')
//                ->hideOnIndex()
//                ->setFormTypeOption('allow_delete' , false),
            ImageField::new('image')
                ->setBasePath('images/products')
                ->setUploadDir('public/images/products')
                ->setUploadedFileNamePattern('/images/products/[slug]-[timestamp].[extension]')
            ->hideOnIndex()
            ->hideOnDetail(),

        ];
    }

}
