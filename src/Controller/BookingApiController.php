<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class BookingApiController extends AbstractController
{
    #[Route('/api/bookings', name: 'api_bookings', methods: ['GET'])]
    public function bookings(BookingRepository $bookingRepository): JsonResponse
    {
        $bookings = $bookingRepository->findAll();

        $data = [];

        foreach ($bookings as $booking) {
            $data[] = [
                'id' => $booking->getId(),
                'title' => $booking->getAppUser()->getUsername(),
                'start' => $booking->getDateStart()->format(\DateTime::ATOM),
                'end' => $booking->getDateEnd()->format(\DateTime::ATOM),
            ];
        }

        return new JsonResponse($data);
    }
}
