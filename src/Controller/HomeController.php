<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', 'app_home')]
    public function home(): RedirectResponse
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->redirectToRoute('eventrooms_list');
    }
}
