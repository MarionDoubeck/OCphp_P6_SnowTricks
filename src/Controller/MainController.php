<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]

    
    /**
     * @Route("/", name="main")
     */
    public function index(GroupRepository $groupRepository, TrickRepository $trickRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'groups' => $groupRepository->findBy([], 
            ['name' => 'asc']),
            'tricks' => $trickRepository->findBy([],
            ['name' => 'asc'])
        ]);
    }
}
