<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for user-related actions.
 */
#[Route('/profil-utilisateur', name: 'profile_')]
class UserController extends AbstractController
{

    
    /**
     * Displays the index page of the user profile.
     *
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }


    /**
     * Displays the page for editing user data.
     *
     * @return Response
     */
    #[Route('/modifier', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'Modifier les données de l\'utilisateur',
        ]);
    }


}
