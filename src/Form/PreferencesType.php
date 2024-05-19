<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreferencesType extends AbstractType
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
			->add('lang', ChoiceType::class, [
				'label' => $this->translator->trans('Langue'),
				'choices' => [
					'Français' => 'fr',
					'English' => 'en',
				],
				'attr' => [
					'class' => 'form-control',
					'autocomplete' => 'lang',
				],
			])
			->add('unit', ChoiceType::class, [
				'label' => $this->translator->trans('Unité de mesure'),
				'choices' => [
					'Standard' => 'standard',
					'Métrique' => 'metric',
					'Impérial' => 'imperial',
				],
				'attr' => [
					'class' => 'form-control',
					'autocomplete' => 'unit',
				],
			])
			->add('country', ChoiceType::class, [
				'label' => $this->translator->trans('Pays'),
				'choices' => [
					'France' => 'fr',
					'Belgique' => 'be',
					'Royaume-Uni' => 'uk',
					'United States' => 'us',
				],
				'attr' => [
					'class' => 'form-control',
					'autocomplete' => 'country',
				],
			])
		;
	}
	
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([]);
	}
}
