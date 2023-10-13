<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    #[Route('/tricks/nouveau-trick', name: 'tricks_add')]
    public function add(): Response
    {
        return $this->render('trick/add.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }

    #[Route('/tricks/{slug}', name: 'tricks_details')]
    public function details(Trick $trick): Response
    {
        return $this->render('trick/details.html.twig', [
            'controller_name' => 'TrickController',
            'trick' => $trick
        ]);
    }

    #[Route('/tricks/{slug}/edit', name: 'tricks_edit')]
    public function edit(Trick $trick): Response
    {
        return $this->render('trick/edit.html.twig', [
            'controller_name' => 'TrickController',
            'trick' => $trick
        ]);
    }
}
