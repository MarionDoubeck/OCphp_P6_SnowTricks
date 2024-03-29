<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Controller for user registration and email verification.
 */
class RegistrationController extends AbstractController
{


    /**
     * Handles the user registration process.
     *
     * @param Request $request The HTTP request.
     * @param UserPasswordHasherInterface $userPasswordHasher The password hasher.
     * @param UserAuthenticatorInterface $userAuthenticator The user authenticator.
     * @param UserAuthenticator $authenticator The authenticator.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param SendMailService $mail The mail service.
     * @param JWTService $jwt The JWT service.
     * 
     * @return Response
     */
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        UserAuthenticatorInterface $userAuthenticator, 
        UserAuthenticator $authenticator, 
        EntityManagerInterface $entityManager, 
        SendMailService $mail,
        JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password.
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Avatar upload.
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile instanceof UploadedFile) {
                // Generate unique filename.
                $newFilename = uniqid().'.'.$avatarFile->guessExtension();

                // Move file to avatars'folder directory.
                $avatarFile->move(
                    $this->getParameter('avatars_directory'),
                    $newFilename
                );

                // Update avatar path in user entity.
                $user->setAvatar($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // Generate user's JWT.
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256',
            ];
            $payload = [
                'user_id' => $user->getId(),
            ];
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Send the mail.
            $mail->send(
                'no-reply@freestyle.net',
                $user->getEmail(),
                'Activez votre compte Snowboard Freestyle',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );

            $this->addFlash('danger', 'Un mail de validation vient de vous être envoyé. Merci d\'ouvrir votre boîte mail pour activer votre compte en cliquant sur le lien. Ce mail sera valable 3h.');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * Handles the email verification of the user.
     *
     * @param string $token The verification token.
     * @param JWTService $jwt The JWT service.
     * @param UserRepository $userRepository The user repository.
     * @param EntityManagerInterface $em The entity manager.
     * 
     * @return Response
     */
    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser(
        $token, 
        JWTService $jwt, 
        UserRepository $userRepository, 
        EntityManagerInterface $em): Response
    {
        if($jwt->isValid($token) === TRUE && $jwt->isExpired($token) === FALSE && $jwt->check($token,  $this->getParameter('app.jwtsecret'))){
            $payload = $jwt->getPayload($token);
            $user = $userRepository->find($payload['user_id']);
            if($user && $user->getIsVerified() === FALSE){
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Votre compte a bien été activé');
                return $this->redirectToRoute('main', ['_fragment' => 'flash']);
            }
        }
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('main', ['_fragment' => 'flash']);
    }


    /**
     * Resends the email verification to the user.
     *
     * @param JWTService $jwt The JWT service.
     * @param SendMailService $mail The mail service.
     * @param UserRepository $userRepository The user repository.
     * 
     * @return Response
     */

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser(); 

        if(!$user){
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');    
        }

        if($user->getIsVerified()){
            $this->addFlash('warning', 'Votre compte est déjà activé');
            return $this->redirectToRoute('main', ['_fragment' => 'flash']);    
        }

        // Generate user's JWT.
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];
        $payload = [
            'user_id' => $user->getId(),
        ];
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // Send the mail.
        $mail->send(
            'no-reply@freestyle.net',
            $user->getEmail(),
            'Activez votre compte Snowboard Freestyle',
            'register',
            [
                'user' => $user,
                'token' => $token
            ]
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('main', ['_fragment' => 'flash']);
    }


}
