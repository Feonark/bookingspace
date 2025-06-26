<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Booking;
use App\Entity\EventRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'html5' => true,
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'html5' => true,
            ])
            ->add('book', SubmitType::class, [
                'label' => 'Demander une réservation',
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var Booking $data */
            $data = $form->getData();

            if ($data->getDateStart() && $data->getDateEnd() && $data->getDateStart() > $data->getDateEnd()) {
                $form->get('dateStart')->addError(new FormError('La date de début ne peut pas être postérieure à la date de fin. '));
            }
            $now = new \DateTimeImmutable('today');
            if ($data->getDateStart() < $now) {
                $form->get('dateStart')->addError(new FormError('La date de début doit être postérieure à aujourd’hui. '));
            }
            if ($data->getDateEnd() < $now) {
                $form->get('dateEnd')->addError(new FormError('La date de fin doit être postérieure à aujourd’hui. '));
            }
        });
    }

    public function getBlockPrefix(): string
    {
        return 'booking_form';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => Booking::class,
            'csrf_token_id' => 'booking_form'
        ]);
    }
}
