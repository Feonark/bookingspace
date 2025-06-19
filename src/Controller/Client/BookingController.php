<?php

namespace App\Controller\Client;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/booking')]
final class BookingController extends AbstractController
{

    public function __construct(private BookingRepository $bookingRepository)
    {

    }

    #[Route('/', name: 'app_booking')]
    public function index(): Response
    {
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
        ]);
    }

    #[Route('/calendar', name: 'app_booking_calendar')]
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }

    #[Route('/list', name: 'app_booking_list')]
    public function list(): Response
    {
        $user = $this->getUser();
        $bookings = $this->bookingRepository->getUserBookings($user);
        return $this->render('booking/list.html.twig',
            ['bookings' => $bookings]);
    }

}
