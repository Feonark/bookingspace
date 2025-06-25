<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\EventRoom;
use App\Entity\Equipment;
use App\Entity\ErgonomicCriteria;
use App\Entity\Notification;
use App\Entity\Software;
use App\Entity\User;
use App\Repository\NotificationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AsDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\BookingCrudController;

#[AsDashboard(name: 'Admin')]
class DashboardController extends AbstractDashboardController
{
    private NotificationRepository $notificationRepository;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(
        NotificationRepository $notificationRepository,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $notifications = $this->notificationRepository->findLatest(); // dernières notif globales
        $unreadNotificationsCount = $this->notificationRepository->countUnread();

        // Générer des liens vers la réservation liée (si elle existe)
        $notificationUrls = [];

        foreach ($notifications as $notification) {
            $booking = $notification->getBooking(); // doit exister dans ton entité Notification

            if ($booking) {
                $url = $this->adminUrlGenerator
                    ->setController(BookingCrudController::class)
                    ->setAction('edit')
                    ->setEntityId($booking->getId())
                    ->generateUrl();

                $notificationUrls[$notification->getId()] = $url;
            } else {
                // Lien de secours
                $notificationUrls[$notification->getId()] = $this->generateUrl('admin_notification_index');
            }
        }

        return $this->render('admin/dashboard.html.twig', [
            'notifications' => $notifications,
            'notificationUrls' => $notificationUrls,
            'unreadNotificationsCount' => $unreadNotificationsCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EeventRoom');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Gestion');
        yield MenuItem::linkToCrud('Réservations', 'fa fa-calendar', Booking::class);
        yield MenuItem::linkToCrud('Salles', 'fa fa-door-open', EventRoom::class);
        yield MenuItem::linkToCrud('Équipements', 'fa fa-plug', Equipment::class);
        yield MenuItem::linkToCrud('Critères Ergonomiques', 'fa fa-chair', ErgonomicCriteria::class);
        yield MenuItem::linkToCrud('Logiciels', 'fa fa-cogs', Software::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
        yield MenuItem::linkToCrud('Notifications', 'fa fa-bell', Notification::class);
    }
}
