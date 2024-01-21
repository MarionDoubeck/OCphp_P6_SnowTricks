<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for handling the reset password functionality.
 */
class ResetPasswordController extends AbstractController
{


    /**
     * Displays the reset password page.
     *
     * @return Response
     */
    #[Route('/reset-password', name: 'app_reset_password')]
    public function index(): Response
    {
        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
        ]);
    }

    
}
