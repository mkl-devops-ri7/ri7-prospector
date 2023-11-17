<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactCrudController extends AbstractController
{
    #[Route('/admin/contact/crud', name: 'app_admin_contact_crud')]
    public function index(): Response
    {
        return $this->render('admin/contact_crud/index.html.twig', [
            'controller_name' => 'ContactCrudController',
        ]);
    }
}
