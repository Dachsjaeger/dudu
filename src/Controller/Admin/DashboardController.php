<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use App\Entity\Aufgabe;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(UserController::class)
            ->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TESTBOARD');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToCrud('User', 'fa fa-home', User::class),
            MenuItem::linkToCrud('To-Dos', 'fa fa-home', Aufgabe::class),
            MenuItem::linkToRoute('Hauptseite', 'fas fa-home', 'home'),
            MenuItem::linkToRoute('TODO', 'fas fa-home', 'todo')
        ];
    }
}
