<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function __invoke(): Response
    {
        $form = $this->createForm(UserType::class, $this->getUser());

        return $this->render('profile/index.html.twig', [
            'form' => $form,
        ]);
    }
}
