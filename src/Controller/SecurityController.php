<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller for security-related actions (login, logout, password reset).
 */
class SecurityController extends AbstractController
{
    /**
     * Handles the login page.
     *
     * @param AuthenticationUtils $authenticationUtils The authentication utility.
     * @return Response
     */
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one.
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user.
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }


    /**
     * Handles the logout action.
     */
    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * Handles the forgotten password page and email sending.
     *
     * @param Request $request The HTTP request.
     * @param UserRepository $userRepository The user repository.
     * @param TokenGeneratorInterface $tokenGeneratorInterface The token generator.
     * @param EntityManagerInterface $em The entity manager.
     * @param SendMailService $mail The mail service.
     * 
     * @return Response
     */
    #[Route(path: '/oubli-mot-de-passe', name: 'forgotten_password')]
    public function forgotten_password(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface $em,
        SendMailService $mail,
    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() === TRUE && $form->isValid() === TRUE){
            // Look for user from username.
            $user = $userRepository->findOneByUsername($form->get('username')->getData());
            if($user){
                // Generate reset Token.
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $em->persist($user);
                $em->flush();

                // Generate reset password link.
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // Send the mail.
                $mail->send(
                    'no-reply@freestyle.net',
                    $user->getEmail(),
                    'Réinitialisez votre mot de passe Snowboard Freestyle',
                    'password_reset',
                    [
                        'user' => $user,
                        'url' => $url
                    ]
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }

            // Else : if $user doesn't exist with this username (===null).
            $this->addFlash('danger', 'Nom d\'utilisateur inconnu');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('security/reset_password_request.html.twig',[
            'requestPassForm' => $form->createView(),
        ]);
    }


    /**
     * Handles the password reset page after clicking on the reset link.
     *
     * @param string $token The reset token.
     * @param Request $request The HTTP request.
     * @param UserRepository $userRepository The user repository.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @param UserPasswordHasherInterface $passwordHasher The password hasher.
     * 
     * @return Response
     */
    #[Route(path: '/oubli-passe/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // Check token.
        $user = $userRepository->findOneByResetToken($token);
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() === TRUE && $form->isValid() === TRUE){
                // Delete token.
                $user->setResetToken(null);
                // Encode the plain password.
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig',[
                'passForm' => $form->createView(),
            ]);
        }
        // Else : if $user doesn't exist with this username (===null).
        $this->addFlash('danger', 'Ce lien n\'est pas valide');
        return $this->redirectToRoute('app_login');
    }
}
