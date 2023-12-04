<?php

namespace App\Controller\Prospection;

use App\Entity\Prospection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class showProspectionController extends AbstractController
{
    #[Route('/prospection/show/{id}', name: 'app_prospection_show')]
    public function __invoke(Prospection $prospection): Response
    {
        return $this->render('prospection/show_prospection/index.html.twig', [
            'prospection' => $prospection,
        ]);
    }
}
