<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // ID masqué dans les formulaires (pas modifiable) mais visible en liste
            IdField::new('id')->hideOnForm(),

            // Affiche username et email partout
            TextField::new('username'),
            TextField::new('phonenumber'),
            TextField::new('company'),
            TextField::new('siret'),
            TextField::new('email'),


        ];
    }
}
