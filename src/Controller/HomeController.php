<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Api;
use App\Service\WindDirection;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchType;

class HomeController extends AbstractController
{
    /**
	 * @var Api
	 */
	private Api $api;

	/**
	 * @var WindDirection
	 */
	private WindDirection $windDirection;
	
    /**
	 * @param Api $api
     * @param WindDirection $windDirection
	 */
	public function __construct(
		Api $api,
		WindDirection $windDirection,
	) {
		$this->api = $api;
		$this->windDirection = $windDirection;
	}
	
	/**
	 * @param Request $request
	 * @return Response
	 * @throws TransportExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 */
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
		/** @var User $user */
		$user = $this->getUser();
		$unit = $user ? $user->getUnit() : 'metric';

		$weather = $this->api->getWeather('Paris', $unit, $user ? $user->getLang() : 'fr');
		$weather = json_decode($weather, true);
		
		$weather = $this->windDirection->addCompasPoint($weather);

		$defaultTowns = ['Lyon', 'Marseille', 'Nice', 'Nantes', 'Bordeaux', 'Lille'];
		$defaultWeathers = [];
		foreach ($defaultTowns as $town) {
			$defaultWeathers[$town] = $this->api->getWeather($town, $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
			$defaultWeathers[$town] = json_decode($defaultWeathers[$town], true);
			$defaultWeathers[$town] = $this->windDirection->addCompasPoint($defaultWeathers[$town]);
		}

		$form = $this->createForm(SearchType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			
			$weather = $this->api->getWeather($data['result'], $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
			$weather = json_decode($weather, true);
			$weather = $this->windDirection->addCompasPoint($weather);

			return $this->render('home/index.html.twig', [
				'weather' => $weather,
				'defaultWeathers' => $defaultWeathers,
				'form' => $form->createView(),
				'unit' => $unit
			]);
		}

        return $this->render('home/index.html.twig', [
			'weather' => $weather,
			'defaultWeathers' => $defaultWeathers,
			'form' => $form->createView(),
	        'unit' => $unit
        ]);
    }
}
