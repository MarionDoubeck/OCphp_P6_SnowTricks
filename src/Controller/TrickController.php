<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    #[Route('/tricks', name: 'tricks_index')]
    public function index(): Response
    {
        return $this->render('trick/add.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }

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
            'trick' => $trick,
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

    #[Route('/tricks/{slug}/delete', name: 'tricks_delete')]
    public function delete(Trick $trick): void
    {
        throw new \LogicException('methode à faire');
    }
}
