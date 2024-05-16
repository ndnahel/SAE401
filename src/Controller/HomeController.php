<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Api;
use App\Service\WindDirection;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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
	 * @var HttpClientInterface
	 */
	private HttpClientInterface $client;
	
    /**
	 * @param Api $api
     * @param WindDirection $windDirection
	 * @param HttpClientInterface $client
	 */
	public function __construct(
		Api $api,
		WindDirection $windDirection,
		HttpClientInterface $client
	) {
		$this->api = $api;
		$this->windDirection = $windDirection;
		$this->client = $client;
	}

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
		/** @var User $user */
		$user = $this->getUser();

		$weather = $this->api->getWeather('Paris', $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
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
				'controller_name' => 'HomeController',
				'weather' => $weather,
				'defaultWeathers' => $defaultWeathers,
				'form' => $form->createView(),
			]);
		}

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
			'weather' => $weather,
			'defaultWeathers' => $defaultWeathers,
			'form' => $form->createView(),
        ]);
    }
}
