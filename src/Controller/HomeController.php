<?php

namespace App\Controller;

use App\Service\Api;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    	/**
	 * @var Api
	 */
	private Api $api;
	
	/**
	 * @var HttpClientInterface
	 */
	private HttpClientInterface $client;
	
    /**
	 * @param Api $api
	 * @param HttpClientInterface $client
	 */
	public function __construct(
		Api $api,
		HttpClientInterface $client
	) {
		$this->api = $api;
		$this->client = $client;
	}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
		/** @var User $user */
		$user = $this->getUser();

		$paris = $this->api->getWeather('Paris', $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
		$paris = json_decode($paris, true);

		$defaultTowns = ['Lyon', 'Marseille', 'Nice', 'Nantes', 'Bordeaux', 'Lille'];
		$defaultWeathers = [];
		foreach ($defaultTowns as $town) {
			$defaultWeathers[$town] = $this->api->getWeather($town, $user ? $user->getUnit() : 'metric', $user ? $user->getLang() : 'fr');
			$defaultWeathers[$town] = json_decode($defaultWeathers[$town], true);
		}
		
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
			'paris' => $paris,
			'defaultWeathers' => $defaultWeathers
        ]);
    }
}
