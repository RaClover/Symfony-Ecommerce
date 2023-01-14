<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->onlyOnIndex(),

            ImageField::new('image')
            ->hideOnForm(),
            TextField::new('fullName')
            ->hideOnForm(),
            TextField::new('firstName')
            ->hideOnIndex(),
            TextField::new('lastName')
            ->hideOnIndex(),
            EmailField::new('email'),
            ArrayField::new('roles')
            ->hideOnIndex()
            ->hideOnDetail(),
            TextField::new('country'),
            TextField::new('city'),
            TextField::new('password'),
            ImageField::new('image')
                ->setBasePath('images/user')
                ->setUploadDir('public/images/user')
                ->setUploadedFileNamePattern('/images/user/[slug]-[timestamp].[extension]')
                ->hideOnIndex()
                ->hideOnDetail(),

        ];
    }


    public function configureActions(Actions $actions): Actions
    {


        return parent::configureActions($actions)
            ->disable(Action::DELETE)
            ;
    }

}
