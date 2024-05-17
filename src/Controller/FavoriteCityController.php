<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\FavoriteCity;
use App\Entity\User;
use App\Repository\FavoriteCityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FavoriteCityController extends AbstractController
{
	/**
	 * @var FavoriteCityRepository
	 */
	private FavoriteCityRepository $fcRepo;
	
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $em;
	
	/**
	 * @var TranslatorInterface
	 */
	private TranslatorInterface $translator;
	
	/**
	 * @param FavoriteCityRepository $fcRepo
	 * @param EntityManagerInterface $em
	 * @param TranslatorInterface $translator
	 */
	public function __construct(
		FavoriteCityRepository $fcRepo,
		EntityManagerInterface $em,
		TranslatorInterface $translator,
	) {
		$this->fcRepo = $fcRepo;
		$this->em = $em;
		$this->translator = $translator;
	}
	
	/**
	 * @param int|null $id
	 * @return Response
	 */
	#[Route('/add-city/{id}', name: 'add_city', methods: ['GET'])]
	public function addCity(
		?int $id,
	): Response
	{
		if (!$id) {
			$this->addFlash('error', $this->translator->trans('La ville n\'a pas été trouvée.'));
			return $this->redirectToRoute('app_home');
		}

		/** @var User $user */
		$user = $this->getUser();
		if (!$user) {
			$this->addFlash('error', $this->translator->trans('Vous devez être connecté pour ajouter une ville en favori.'));
			return $this->redirectToRoute('app_home');
		}

		$fc = $this->fcRepo->findBy(['city_id' => $id, 'user' => $user]);
		if (!$fc) {
			$fc = new FavoriteCity();
			$fc->setCityId($id);
			$fc->setUser($user);
			$this->em->persist($fc);
			$this->em->flush();
			$this->addFlash('success', $this->translator->trans('La ville a bien été ajoutée en favori.'));
		}
		
		return $this->redirectToRoute('app_home');
	}
	
	/**
	 * @param int|null $id
	 * @return Response
	 */
	#[Route('/remove-city/{id}', name: 'remove_city', methods: ['GET'])]
	public function removeCity(
		?int $id,
	): Response
	{
		if (!$id) {
			$this->addFlash('error', $this->translator->trans('La ville n\'a pas été trouvée.'));
			return $this->redirectToRoute('app_home');
		}
		
		/** @var User $user */
		$user = $this->getUser();
		if (!$user) {
			$this->addFlash('error', $this->translator->trans('Vous devez être connecté pour supprimer une ville en favori.'));
			return $this->redirectToRoute('app_home');
		}
		
		$fc = $this->fcRepo->findOneBy(['city_id' => $id, 'user' => $user]);
		if ($fc) {
			$this->em->remove($fc);
			$this->em->flush();
			$this->addFlash('success', $this->translator->trans('La ville a bien été supprimée des favoris.'));
			$this->em->flush();
		}
		
		return $this->redirectToRoute('app_home');
	}
}
