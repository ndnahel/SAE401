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
use App\Service\Api;

class HomeController extends AbstractController
{

	/**
	 * @var Api
	 */
	private Api $api;

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
		Api $api,
		WeatherService $weatherService,
		ForecastService $forecastService
	) {
		$this->api = $api;
		$this->weatherService = $weatherService;
		$this->forecastService = $forecastService;
	}

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
		/** @var User $user */
		$user = $this->getUser();
        $unit = $user ? $user->getUnit() : 'metric';
		
		$userConnected = $user ? true : false;

		// Main section weather -> default = Paris
		$weather = $this->weatherService->getWeatherData('Paris', $unit, $user ? $user->getLang() : 'fr');

		$forecast = $this->forecastService->getForecastData('Paris', $unit, $user ? $user->getLang() : 'fr');
		$forecastList = $this->forecastService->formatForecastData($forecast);

		// Checking if city is in favs
		$favoriteCities = $user ? $user->getFavoriteCities()->toArray() : [];
		$cityIds = array_map(function($favoriteCity) {
			return $favoriteCity->getCityId();
		}, $favoriteCities);
		
		$favoriteCities = [];

		foreach ($cityIds as $cityId) {
			$cityWeather = $this->api->getWeatherById($cityId, $unit, $user ? $user->getLang() : 'fr');
			$cityForecast = $this->api->getForecastById($cityId, $unit, $user ? $user->getLang() : 'fr');

			$favoriteCities[] = [
				'id' => $cityId,
				'weather' => $cityWeather['content'],
				'forecast' => $cityForecast['content']
			];
		}

		// Right section with weathers around France (default)
		$defaultTowns = ['Lyon', 'Marseille', 'Nice', 'Nantes', 'Bordeaux', 'Lille'];
		$defaultWeathers = [];
		foreach ($defaultTowns as $town) {
			$defaultWeathers[$town] = $this->weatherService->getWeatherData($town, $unit, $user ? $user->getLang() : 'fr');
		}

		$form = $this->createForm(SearchType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			
			$weather = $this->weatherService->getWeatherData($data['result'], $unit, $user ? $user->getLang() : 'fr');

			$forecast = $this->forecastService->getForecastData($data['result'], $unit, $user ? $user->getLang() : 'fr');
			$forecastList = $this->forecastService->formatForecastData($forecast);

			return $this->render('home/index.html.twig', [
				'controller_name' => 'HomeController',
				'weather' => $weather,
				'forecastList' => $forecastList,
				'defaultWeathers' => $defaultWeathers,
				'form' => $form->createView(),
                'unit' => $unit,
				'userConnected' => $userConnected,
				'favoriteCities' => $favoriteCities
			]);
		}

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
			'weather' => $weather,
			'forecastList' => $forecastList,
			'defaultWeathers' => $defaultWeathers,
			'form' => $form->createView(),
            'unit' => $unit,
			'userConnected' => $userConnected,
			'favoriteCities' => $favoriteCities
        ]);
    }
}