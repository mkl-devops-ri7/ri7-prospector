<?php

namespace App\Controller;

use App\Entity\Enum\ProspectionTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewProspectionController extends AbstractController
{
    #[Route('/new/prospection', name: 'app_new_prospection')]
    public function index(): Response
    {
        return $this->render('new_prospection/index.html.twig', [
            'types' => ProspectionTypeEnum::cases(),
        ]);
    }
}
