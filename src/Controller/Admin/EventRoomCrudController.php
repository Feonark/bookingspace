<?php

namespace App\Controller\Admin;

use App\Entity\EventRoom;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class EventRoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventRoom::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),

            TextField::new('description')->onlyOnIndex(),

            IntegerField::new('capacity'),

            TextEditorField::new('description')->onlyOnForms(),
        ];
    }
}
