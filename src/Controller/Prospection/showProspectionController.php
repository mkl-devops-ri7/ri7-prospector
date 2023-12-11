<?php

namespace App\Controller\Prospection;

use App\Entity\Prospection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class showProspectionController extends AbstractController
{
    #[Route('/prospection/show/{id}', name: 'app_prospection_show')]
    #[IsGranted(
        attribute: new Expression('user === subject'),
        subject: new Expression('args["prospection"].getUser()'),
    )]
    public function __invoke(Prospection $prospection): Response
    {
        return $this->render('prospection/show_prospection/index.html.twig', [
            'prospection' => $prospection,
        ]);
    }
}
