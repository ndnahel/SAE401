<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
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
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
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
			
			//$url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
			
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
}
