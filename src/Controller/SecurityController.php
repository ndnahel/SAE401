<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\PreferencesType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    /**
     * @throws UserNotFoundException
     * @throws TransportExceptionInterface
     */
    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function request(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ForgotPasswordType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                throw new UserNotFoundException('Utilisateur non trouvé.');
            }

            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);
            $entityManager->flush();

            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $url = null;

            $email = (new Email())
                //->from('mmi22d06@mmi-troyes.fr')
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->html("Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href='$url'>$url</a>");

            $mailer->send($email);
            $this->addFlash('success', 'Un email de réinitialisation de votre mot de passe a été envoyé.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string|null $token
     * @param UserRepository $userRepo
     * @return Response
     * @throws UserNotFoundException
     */
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        ?string $token,
        UserRepository $userRepo
    ): Response {
        $user = $userRepo->findOneBy(['resetToken' => $token]);
        if (!$user) {
            throw new UserNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('reset_password/reset.html.twig', [
            'token' => $token,
        ]);
    }
	
	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	#[Route('/preferences', name: 'app_preferences', methods: ['GET', 'POST'])]
	public function updatePreferences(Request $request, EntityManagerInterface $entityManager): Response
	{
		/** @var User $user */
		$user = $this->getUser();
		if (!$user) {
			return $this->redirectToRoute('app_login');
		}
		
		$form = $this->createForm(PreferencesType::class)->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$user->setPreferences($form->getData());
			$entityManager->flush();
			$this->addFlash('success', 'Vos préférences ont été mises à jour.');
		}
		
		$userForm = $this->createForm(UserType::class, $user)->handleRequest($request);
		if ($userForm->isSubmitted() && $userForm->isValid()) {
			dd($userForm->getData());
			$entityManager->flush();
			$this->addFlash('success', 'Vos paramètres ont été mis à jour.');
		}

		return $this->render('security/preferences.html.twig', [
			'form' => $form->createView(),
			'userForm' => $userForm->createView(),
		]);
	}
}
