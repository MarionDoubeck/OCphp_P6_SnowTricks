<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trick', name: 'app_trick')]
class TrickController extends AbstractController
{
    #[Route('/id', name: 'details')]
    public function index(): Response
    {
        return $this->render('trick/details.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }


    #[Route('/ajouter', name: 'add')]
    public function add(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }


    #[Route('/modifier', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }


    #[Route('/supprimer', name: 'delete')]
    public function delete(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }
}
