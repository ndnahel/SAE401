<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\WeatherService;
use App\Service\ForecastService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchType;

class HomeController extends AbstractController
{

	/**
	 * @var WeatherService
	 */
	private WeatherService $weatherService;

	/**
	 * @var ForecastService
	 */
	private ForecastService $forecastService;
	
    /**
	 * @param Api $api
	 * @param WeatherService $weatherService
	 * @param ForecastService $forecastService
	 */
	public function __construct(
		WeatherService $weatherService,
		ForecastService $forecastService
	) {
		$this->weatherService = $weatherService;
		$this->forecastService = $forecastService;
	}

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
		/** @var User $user */
		$user = $this->getUser();

		// Main section weather -> default = Paris
		$weather = $this->weatherService->getWeatherData('Paris', $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');

		$forecast = $this->forecastService->getForecastData('Paris', $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
		$forecastList = $this->forecastService->formatForecastData($forecast);

		// Right section with weathers around France (default)
		$defaultTowns = ['Lyon', 'Marseille', 'Nice', 'Nantes', 'Bordeaux', 'Lille'];
		$defaultWeathers = [];
		foreach ($defaultTowns as $town) {
			$defaultWeathers[$town] = $this->weatherService->getWeatherData($town, $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
		}

		$form = $this->createForm(SearchType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			
			$weather = $this->weatherService->getWeatherData($data['result'], $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');

			$forecast = $this->forecastService->getForecastData($data['result'], $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
			$forecastList = $this->forecastService->formatForecastData($forecast);

			return $this->render('home/index.html.twig', [
				'controller_name' => 'HomeController',
				'weather' => $weather,
				'forecastList' => $forecastList,
				'defaultWeathers' => $defaultWeathers,
				'form' => $form->createView(),
			]);
		}

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
			'weather' => $weather,
			'forecastList' => $forecastList,
			'defaultWeathers' => $defaultWeathers,
			'form' => $form->createView(),
        ]);
    }
}
