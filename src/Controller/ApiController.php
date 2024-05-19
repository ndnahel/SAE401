<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiController extends AbstractController
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
	 * @param HttpClientInterface $client
	 * @param Api $api
	 */
	public function __construct(Api $api, HttpClientInterface $client) {
		$this->api = $api;
		$this->client = $client;
	}

	/**
	 * @return string
	 */
	public function getApiKey(): string
	{
		return $_ENV['API_KEY'];
	}

	#[Route('/api/weatherById/{id}/{unit}/{lang}', name: 'api_weatherById')]
	public function weatherById(
		string $id,
		?string $unit = 'metric',
		?string $lang = 'fr'
	) : Response {
		/** @var User $user */
		$user = $this->getUser();

		$response = $this->api->getWeatherById($id, $user ? $user->getUnit() : $unit, $user ? $user->getLang() : $lang);

		return new Response(
			json_encode($response),
			200 ,
			['content-type' => 'application/json']
		);
	}
	
	/**
	 * @param string $location
	 * @param string|null $unit
	 * @param string|null $lang
	 * @return Response
	 * @throws TransportExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 */
	#[Route('/api/{endpoint}/{location}/{unit}/{lang}', name: 'api_weather')]
	public function apiRequest(
		string $endpoint,
		string $location,
		?string $unit = 'metric',
		?string $lang = 'fr'
	) : Response {
		/** @var User $user */
		$user = $this->getUser();

		$response = '';
		if ($endpoint === 'weather') {
			$response = $this->api->getWeather(
				$location,
				$user ? $user->getUnit() : $unit,
				$user ? $user->getLang() : $lang
			);
		} elseif ($endpoint === 'forecast') {
			$response = $this->api->getForecast(
				$location,
				$user ? $user->getUnit() : $unit,
				$user ? $user->getLang() : $lang
			);
		}

		return new Response(
			$response,
			200 ,
			['content-type' => 'application/json']
		);
	}

	#[Route('/api/user_preferences', name: 'api_user')]
	public function apiUserPreferences() : Response {
		/** @var User $user */
		$user = $this->getUser();
		$unit = $user ? $user->getUnit() : 'metric';
		$lang = $user ? $user->getLang() : 'fr';
		
		$response = [
			'unit' => $unit,
			'lang' => $lang
		];
		
		return new Response(
			json_encode($response),
			200,
			['content-type' => 'application/json']
		);
	}
}
