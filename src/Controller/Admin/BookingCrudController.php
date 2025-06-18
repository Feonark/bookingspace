<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Enum\BookingStatus;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
            DateField::new('dateStart', 'Date de début'),
            DateField::new('dateEnd', 'Date de fin'),

            ChoiceField::new('bookingStatus', 'Statut')
                ->setChoices([
                    'En attente' => BookingStatus::PENDING,
                    'Confirmée' => BookingStatus::CONFIRMED,
                    'Annulée' => BookingStatus::CANCELLED,
                ])
                ->renderAsBadges([
                    BookingStatus::CONFIRMED->value => 'success', // vert
                    BookingStatus::PENDING->value => 'warning',   // orange
                    BookingStatus::CANCELLED->value => 'danger',  // rouge
                ])
                ->formatValue(function ($value, $entity) {
                    return match ($value?->value) {
                        'confirmed' => '<i class="fas fa-check-circle"></i> Confirmée',
                        'pending' => '<i class="fas fa-hourglass-half"></i> En attente',
                        'cancelled' => '<i class="fas fa-times-circle"></i> Annulée',
                        default => '',
                    };
                })
                ->onlyOnIndex(), // affiche les badges dans la liste uniquement

            ChoiceField::new('bookingStatus', 'Statut') // champ classique dans formulaire
            ->setChoices([
                'En attente' => BookingStatus::PENDING,
                'Confirmée' => BookingStatus::CONFIRMED,
                'Annulée' => BookingStatus::CANCELLED,
            ])
                ->onlyOnForms(), // uniquement dans formulaire
        ];
    }
}
