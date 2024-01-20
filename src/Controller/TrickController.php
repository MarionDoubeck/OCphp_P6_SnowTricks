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
    /**
     * Process the form for adding or editing a trick.
     *
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @param bool $isEdit
     * @return Response
     */
    private function processTrickForm(
        Trick $trick, 
        Request $request, 
        SluggerInterface $slugger, 
        EntityManagerInterface $em,
        bool $isEdit = false
    ): Response
    {
        $addTrickForm = $this->createForm(AddTrickFormType::class, $trick);
        $addTrickForm->handleRequest($request);

        if ($addTrickForm->isSubmitted() && $addTrickForm->isValid()) {
            if (!$isEdit && $this->checkIfTrickExists($em, $trick->getName())){
                $this->addFlash('danger','Ce trick existe déjà');
                return $this->redirectToRoute('tricks_add');
            } else {
                $slug = $slugger->slug(mb_strtolower($trick->getName(), 'UTF-8'));
                $trick->setSlug($slug);
                $trick->setUser($this->getUser());
                // Featured img.
                $featuredImg = $addTrickForm->get('isFeatured')->getData();
                if ($featuredImg !== null){
                    $newFilename = uniqid().'.'.$featuredImg->guessExtension();
                    $featuredImg->move(
                        $this->getParameter('media_directory'),
                        $newFilename
                    );
                    $newMedia = new Media;
                    $newMedia->setPath($newFilename);
                    $newMedia->setTrick($trick);
                    $newMedia->setDescription($trick->getName());
                    $newMedia->setType('image');
                    $em->persist($newMedia);
                    $trick->setFeaturedImg($newMedia);
                }
                // Other media.
                $this->processImageUpload($addTrickForm, $trick, $em, $featuredImg);
                $this->processVideoCode($addTrickForm, $trick, $em);

                $em->persist($trick);
                $em->flush();

                if(! $isEdit){
                    $this->addFlash('success','Votre trick à bien été publié');
                    return $this->redirectToRoute('main', ['_fragment' => 'flash']);
                }else{
                    $this->addFlash('success','Votre trick à bien été modifié');
                    return $this->redirectToRoute('main', ['_fragment' => 'flash']);
                }
            }
        }
        if(! $isEdit){
            return $this->render('trick/add.html.twig', [
                'controller_name' => 'TrickController',
                'addTrickForm' => $addTrickForm->createView(),
            ]);
        }else{
            return $this->render('trick/edit.html.twig', [
                'controller_name' => 'TrickController',
                'trick' => $trick,
                'addTrickForm' => $addTrickForm->createView(),
            ]);
        }
    }


    /**
     * Process the image upload for a trick.
     *
     * @param $form
     * @param $trick
     * @param $em
     * @param $featuredImg
     */
    private function processImageUpload($form, $trick, $em, $featuredImg)
    {
        $imageFiles = $form->get('images')->getData();
        $first =0;
        foreach ($imageFiles as $imageFile) {
            if ($imageFile !== null){
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('media_directory'),
                    $newFilename
                );
                $newMedia = new Media;
                $newMedia->setPath($newFilename);
                $newMedia->setTrick($trick);
                $newMedia->setDescription($trick->getName());
                $newMedia->setType('image');
                $em->persist($newMedia);
                if($featuredImg === null && $first === 0){
                    $trick->setFeaturedImg($newMedia);
                    $first++;
                }
            }
        }
    }


    /**
     * Process the video code for a trick.
     *
     * @param $form
     * @param $trick
     * @param $em
     */
    private function processVideoCode($form, $trick, $em)
    {
        $VideoCodes = $form->get('videoEmbdedCode')->getData();
        foreach ($VideoCodes as $mediaPath) {
            if ($mediaPath !== null){
                // Unable autoplay.
                if (strpos($mediaPath, "autoplay=1") !== false) {
                    $mediaPath = str_replace("autoplay=1", "autoplay=0", $mediaPath);
                    $mediaPath = str_replace("autoplay=true", "autoplay=false", $mediaPath);
                }
                $newMedia = new Media;
                $newMedia->setPath($mediaPath);
                $newMedia->setTrick($trick);
                $newMedia->setDescription($trick->getName());
                $newMedia->setType('video');
                $em->persist($newMedia);
            }
        }
    }


    /**
     * Handles the request for adding a new trick.
     *
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/tricks/nouveau-trick', name: 'tricks_add')]
    public function add(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response
    {
        $newTrick = new Trick;
        return $this -> processTrickForm($newTrick, $request, $slugger, $em, false);
    }


    /**
     * Displays the details of a trick.
     *
     * @param Trick $trick
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param Comment $comment
     * @param EntityManagerInterface $em
     * @return Response
     */
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


    /**
     * Handles the request for editing a trick.
     *
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/tricks/{slug}/edit', name: 'tricks_edit')]
    public function edit(
        Trick $trick,
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response
    {
        {
            return $this -> processTrickForm($trick, $request, $slugger, $em, true);
        }
    }


    /**
     * Handles the request for deleting a trick.
     *
     * @param Trick $trick
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/tricks/{slug}/delete', name: 'tricks_delete')]
    public function delete(Trick $trick, EntityManagerInterface $em): Response
    {
        $featuredImg = $trick->getFeaturedImg();
        if($featuredImg){
            $em->remove($featuredImg);
            $trick->removeFeaturedImg();
            $em->flush();
        }
        foreach ($trick->getMedia() as $media) {
            $em->remove($media);
        }
        $em->remove($trick);
        $em->flush();

        $this->addFlash('success', 'Votre trick a bien été supprimé');
        return $this->redirectToRoute('main', ['_fragment' => 'flash']);
    }


    /**
     * Handles the request for deleting a media associated with a trick.
     *
     * @param Media $media
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/tricks/media/{media}/delete', name: 'media_delete')]
    public function deleteMedia(Media $media, EntityManagerInterface $em): Response
    {
        $trick = $media->getTrick();
        $trick->removeMedium($media);

        $mediaPath = $this->getParameter('media_directory') . '/' . $media->getPath();
        if (file_exists($mediaPath)) {
            unlink($mediaPath);
        }

        $em->remove($media);
        $em->flush();

        $this->addFlash('success', 'Le média a bien été supprimé');
        return $this->redirectToRoute('tricks_details', ['slug' => $trick->getSlug()]);
    }

    
    /**
     * Checks if a trick with the given name already exists in the database.
     *
     * @param EntityManagerInterface $entityManager
     * @param string $trickName
     * @return bool
     */
    public function checkIfTrickExists(EntityManagerInterface $entityManager, string $trickName): bool
    {
        $trickRepository = $entityManager->getRepository(Trick::class);
        $existingTrick = $trickRepository->findOneBy(['name' => $trickName]);

        return $existingTrick !== null;
    }
}

