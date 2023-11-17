<?php

namespace App\Controller\Admin;

use App\Entity\Action;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Notification;
use App\Entity\Prospection;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * Index controller.
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        /** @var AdminUrlGenerator $routeBuilder */
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'app_home');
        yield MenuItem::linkToCrud('User', 'fas fa-map-marker-alt', User::class);
        yield MenuItem::linkToCrud('Company', 'fas fa-map-marker-alt', Company::class);
        yield MenuItem::linkToCrud('Notification', 'fas fa-map-marker-alt', Notification::class);
        yield MenuItem::linkToCrud('Action', 'fas fa-map-marker-alt', Action::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-map-marker-alt', Contact::class);
        yield MenuItem::linkToCrud('Prospection', 'fas fa-map-marker-alt', Prospection::class);
    }
}
