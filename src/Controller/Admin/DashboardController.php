<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Equipment;
use App\Entity\EventRoom;
use App\Entity\ErgonomicCriteria;
use App\Entity\Notification;
use App\Entity\Software;
use App\Entity\User;
use App\Repository\NotificationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AsDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[AsDashboard(name: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private NotificationRepository $notificationRepository;
    private Security $security;

    public function __construct(NotificationRepository $notificationRepository, Security $security)
    {
        $this->notificationRepository = $notificationRepository;
        $this->security = $security;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $unreadCount = $this->notificationRepository->countUnreadForUser();
        // Affiche ton dashboard personnalisé dans le layout EasyAdmin
        return parent::index()->setContent(
            $this->renderView('admin/dashboard.html.twig', [
                'unreadNotificationsCount' => $unreadCount,
            ])
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BookingSpace')
            ->setTitle('My Admin');
    }

    public function configureMenuItems(): iterable
    {
        $unreadCount = $this->notificationRepository->countUnreadForUser();

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Notifications', 'fas fa-bell', Notification::class)
            ->setBadge($unreadCount > 0 ? (string)$unreadCount : null, 'danger');

        yield MenuItem::linkToCrud('EventRoom', 'fas fa-door-open', EventRoom::class);
        yield MenuItem::linkToCrud('Equipment', 'fas fa-tools', Equipment::class);
        yield MenuItem::linkToCrud('Booking', 'fas fa-calendar-check', Booking::class);
        yield MenuItem::linkToCrud('Ergonomic Criteria', 'fas fa-brain', ErgonomicCriteria::class);
        yield MenuItem::linkToCrud('Software', 'fas fa-laptop-code', Software::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }
}
