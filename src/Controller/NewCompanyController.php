<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewCompanyController extends AbstractController
{
    #[Route('/new/company', name: 'app_new_company')]
    public function index(): Response
    {
        return $this->render('new_company/index.html.twig', [
            'controller_name' => 'NewCompanyController',
        ]);
    }
}
