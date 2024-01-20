<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for the main page.
 */
class MainController extends AbstractController
{
    /**
     * Displays the main page with categories and tricks.
     *
     * @param CategoryRepository $categoryRepository
     * @param TrickRepository $trickRepository
     * @return Response
     */
    #[Route('/', name: 'main')]
    public function index(CategoryRepository $categoryRepository, TrickRepository $trickRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'categories' => $categoryRepository->findBy([], 
            ['name' => 'asc']),
            'tricks' => $trickRepository->findBy([],
            ['name' => 'asc'])
        ]);
    }
}
