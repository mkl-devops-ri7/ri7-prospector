<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewContactController extends AbstractController
{
    #[Route('/new/contact', name: 'app_new_contact')]
    public function index(): Response
    {
        return $this->render('new_contact/index.html.twig', [
            'controller_name' => 'NewContactController',
        ]);
    }
}
