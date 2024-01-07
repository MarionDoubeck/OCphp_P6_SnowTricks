<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Media;
use App\Form\AddTrickFormType;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickController extends AbstractController
{
    #[Route('/tricks/nouveau-trick', name: 'tricks_add')]
    public function add(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response
    {
        $newTrick = new Trick;
        $addTrickForm = $this->createForm(AddTrickFormType::class, $newTrick);
        $addTrickForm->handleRequest($request);
        

        if ($addTrickForm->isSubmitted() && $addTrickForm->isValid()) {
            if ($this->checkIfTrickExists($em, $newTrick->getName())){
                $this->addFlash('danger','Ce trick existe déjà');
                return $this->redirectToRoute('tricks_add');
            } else {
                $slug = $slugger->slug(mb_strtolower($newTrick->getName(), 'UTF-8'));
                $newTrick->setSlug($slug);
                $newTrick->setUser($this->getUser());

                //media upload
                $mediaFiles = $addTrickForm->get('media')->getData();
                foreach ($mediaFiles as $mediaItem) {
                    $mediaFile = $mediaItem['file'];
                    if ($mediaFile instanceof UploadedFile) {
                        // Generate unique filename
                        $newFilename = uniqid().'.'.$mediaFile->guessExtension();

                        // Move file to medias'folder directory
                        $mediaFile->move(
                            $this->getParameter('media_directory'),
                            $newFilename
                        );

                        //create new media 
                        $newMedia = new Media;
                        $newMedia->setPath($newFilename);
                        $newMedia->setTrick($newTrick);
                        $newMedia->setDescription($newTrick->getName());
                        $mimeType = $mediaFile->getClientMimeType();

                        if (strpos($mimeType, 'image') === 0) {
                            $newMedia->setType('image');
                        } elseif (strpos($mimeType, 'video') === 0) {
                            $newMedia->setType('video');
                        } else {
                            throw new \LogicException('format non pris en charge');
                        }
                        $em->persist($newMedia);
                    } else {
                    throw new \LogicException('Le fichier n\'est pas une instance de UploadedFile. Contenu : ' . var_export($mediaFile, true));
                    }
                }

                $em->persist($newTrick);
                $em->flush();

                $this->addFlash('success','Votre trick à bien été publié');
                return $this->redirectToRoute('main', ['_fragment' => 'flash']);
            }
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
    public function edit(
        Trick $trick,
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response
    {
        $addTrickForm = $this->createForm(AddTrickFormType::class, $trick);
        $addTrickForm->handleRequest($request);
        

        if ($addTrickForm->isSubmitted() && $addTrickForm->isValid()) {
            $slug = $slugger->slug(mb_strtolower($trick->getName(), 'UTF-8'));
            $trick->setSlug($slug);
            $trick->setUser($this->getUser());
            $em->persist($trick);
            $em->flush();

            $this->addFlash('success','Votre trick à bien été modifié');
            return $this->redirectToRoute('main', ['_fragment' => 'flash']);
        }

        return $this->render('trick/edit.html.twig', [
            'controller_name' => 'TrickController',
            'trick' => $trick,
            'addTrickForm' => $addTrickForm->createView(),
        ]);
    }

    #[Route('/tricks/{slug}/delete', name: 'tricks_delete')]
    public function delete(Trick $trick, EntityManagerInterface $em): Response
    {
        $em->remove($trick);
        $em->flush();

        $this->addFlash('success', 'Votre trick a bien été supprimé');
        return $this->redirectToRoute('main', ['_fragment' => 'flash']);
    }

    #[Route('/tricks/{media}/delete', name: 'media_delete')]
    public function deleteMedia(Media $media, EntityManagerInterface $em): Response
    {
        $trick = $media->getTrick();
        $trick->removeMedium($media);

        $em->remove($media);
        $em->flush();

        $this->addFlash('success', 'Le média a bien été supprimé');
        return $this->redirectToRoute('tricks_details', ['slug' => $trick->getSlug()]);
    }

    
    public function checkIfTrickExists(EntityManagerInterface $entityManager, string $trickName): bool
    {
        $trickRepository = $entityManager->getRepository(Trick::class);
        $existingTrick = $trickRepository->findOneBy(['name' => $trickName]);

        return $existingTrick !== null;
    }
}
