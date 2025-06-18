<?php

namespace App\Controller\Admin;

use App\Entity\Equipment;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\EventRoom;
use App\Entity\Booking;
use App\Entity\User;
use App\Entity\ErgonomicCriteria;
use App\Entity\Software;
use App\Entity\Notification;



#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BookingSpace');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('EventRoom', 'fas fa-door-open', EventRoom::class);
        yield MenuItem::linkToCrud('Notification', 'fas fa-door-open', Notification::class);
        yield MenuItem::linkToCrud('Equipment', 'fas fa-tools', Equipment::class);
        yield MenuItem::linkToCrud('Booking', 'fas fa-calendar-check', Booking::class);
        yield MenuItem::linkToCrud('Ergonomic Criteria', 'fas fa-brain', ErgonomicCriteria::class);
        yield MenuItem::linkToCrud('Software', 'fas fa-laptop-code', Software::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }
}
