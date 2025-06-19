<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\EventRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart')
            ->add('dateEnd')
            ->add('book', SubmitType::class, [
                'label' => 'Booker',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'booking_form';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'csrf_protection' => true,
            'data_class' => Booking::class,
            'csrf_token_id' => 'booking_form'
        ]);
    }
}
