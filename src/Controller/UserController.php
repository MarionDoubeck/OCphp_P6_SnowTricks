<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/profil-utilisateur', name: 'profile_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/modifier', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'Modifier les données de l\'utilisateur',
        ]);
    }
}
