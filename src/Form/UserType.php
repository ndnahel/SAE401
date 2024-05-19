<?php

namespace App\Form;
	
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
	
class UserType extends AbstractType
{
	/**
	 * @var TranslatorInterface
	 */
	private TranslatorInterface $translator;
	
	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('username', TextType::class, [
				'label' => $this->translator->trans('Nom d\'utilisateur'),
			])
			->add('email', EmailType::class, [
				'label' => $this->translator->trans('Adresse email'),
			])
			->add('password', PasswordType::class, [
				'label' => $this->translator->trans('Mot de passe'),
				'mapped' => false,
			])
			->add('newPassword', PasswordType::class, [
				'label' => $this->translator->trans('Nouveau mot de passe'),
				'mapped' => false,
				'required' => false,
				'data' => '',
				'constraints' => [
					new NotBlank([
						'message' => 'Please enter a password',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Your password should be at least {{ limit }} characters',
						'max' => 4096,
					]),
				],
			])
			->add('newPassword2', PasswordType::class, [
				'label' => $this->translator->trans('Confirmer le nouveau mot de passe'),
				'mapped' => false,
				'required' => false,
				'data' => '',
				'constraints' => [
					new NotBlank([
						'message' => 'Please enter a password',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Your password should be at least {{ limit }} characters',
						'max' => 4096,
					]),
				],
			])
		;
	}
	
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
