<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Enum\BookingStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            AssociationField::new('user', 'Client')
                ->formatValue(fn ($user) => $user?->getName()),

            DateField::new('dateStart', 'Date de début'),
            DateField::new('dateEnd', 'Date de fin'),

            // ✅ Statut avec badges colorés
            ChoiceField::new('bookingStatus', 'Statut')
                ->setChoices([
                    'En attente' => BookingStatus::PENDING,
                    'Confirmée' => BookingStatus::CONFIRMED,
                    'Annulée' => BookingStatus::CANCELLED,
                ])
                ->renderAsBadges([
                    BookingStatus::PENDING->value => 'warning',   // jaune
                    BookingStatus::CONFIRMED->value => 'success', // vert
                    BookingStatus::CANCELLED->value => 'danger',  // rouge
                ])
                ->onlyOnIndex(),

            // ✅ Statut simple (formulaire)
            ChoiceField::new('bookingStatus', 'Statut')
                ->setChoices([
                    'En attente' => BookingStatus::PENDING,
                    'Confirmée' => BookingStatus::CONFIRMED,
                    'Annulée' => BookingStatus::CANCELLED,
                ])
                ->onlyOnForms(),
        ];
    }
}
