<?php

namespace App\Controller;

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
	
	/**
	 * @param string $city
	 * @param string|null $unit
	 * @param string|null $lang
	 * @return Response
	 * @throws TransportExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 */
	#[Route('/api/weather/{city}/{unit}/{lang}', name: 'api_weather')]
	public function index(
		string $city,
		?string $unit = 'metric',
		?string $lang = 'fr'
	) : Response {
		$response = $this->client->request(
			'GET',
			'https://api.openweathermap.org/data/2.5/weather',
			[
				'query' => [
					'q' => $city,
					'appid' => $this->api->getApiKey(),
					'units' => $unit,
					'lang' => $lang,
				],
			]
		);
		
		return new Response(
			$response->getContent(),
			$response->getStatusCode(),
			['content-type' => 'application/json']
		);
	}
}
