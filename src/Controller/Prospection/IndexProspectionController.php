<?php

namespace App\Controller\Prospection;

use App\Repository\ProspectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexProspectionController extends AbstractController
{
    #[Route('/prospection/index/prospection', name: 'app_prospection_index')]
    public function __invoke(ProspectionRepository $prospectionRepository): Response
    {
        return $this->render('prospection/index_prospection/index.html.twig', [
            'prospections' => $prospectionRepository->findBy(
                criteria: ['user' => $this->getUser()],
                orderBy: ['id' => 'DESC']
            ),
        ]);
    }
}
