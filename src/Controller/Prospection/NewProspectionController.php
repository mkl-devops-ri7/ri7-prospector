<?php

namespace App\Controller\Prospection;

use App\Entity\Enum\ProspectionTypeEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewProspectionController extends AbstractController
{
    #[Route('/prospection/new', name: 'app_prospection_new')]
    public function __invoke(): Response
    {
        return $this->render('prospection/new_prospection/index.html.twig', [
            'types' => ProspectionTypeEnum::cases(),
        ]);
    }
}
