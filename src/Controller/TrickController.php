<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\AddTrickFormType;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    #[Route('/tricks/nouveau-trick', name: 'tricks_add')]
    public function add(Request $request): Response
    {
        $newTrick = new Trick;
        $addTrickForm = $this->createForm(AddTrickFormType::class, $newTrick);
        $addTrickForm->handleRequest($request);
        if ($addTrickForm->isSubmitted() && $addTrickForm->isValid()) {
            $newTrickData = $addTrickForm->getData();
            dd($newTrickData);
        }

        return $this->render('trick/add.html.twig', [
            'controller_name' => 'TrickController',
            'addTrickForm' => $addTrickForm->createView(),
        ]);
    }

    #[Route('/tricks/{slug}', name: 'tricks_details')]
    public function details(
        Trick $trick, 
        Request $request,
        CommentRepository $commentRepository,
        Comment $comment,
        EntityManagerInterface $em,
    ): Response
    {
        $commentForm = $this->createForm(CommentFormType::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentData = $commentForm->getData();
            $comment = new Comment();
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $comment->setContent($commentData->getContent());
            $comment->setCreatedAt(new \DateTimeImmutable);
            $em->persist($comment);
            $em->flush();
            unset($commentForm);
            $commentForm = $this->createForm(CommentFormType::class);
            $this->addFlash('success','Votre commentaire à bien été publié');
            return $this->redirectToRoute('tricks_details', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/details.html.twig', [
            'controller_name' => 'TrickController',
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
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
