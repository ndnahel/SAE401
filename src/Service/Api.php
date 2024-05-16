<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
	/**
	 * @var HttpClientInterface
	 */
	private HttpClientInterface $client;
	
	/**
	 * @param HttpClientInterface $client
	 */
	public function __construct(HttpClientInterface $client)
	{
		$this->client = $client;
	}

	/**
	 * @return string
	 */
	public function getApiKey(): string
	{
		return $_ENV['API_KEY'];
	}
	
	/**
	 * @param string $city
	 * @param string $unit
	 * @param string $lang
	 * @return string
	 * @throws TransportExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 */
	public function getWeather(string $unit = 'metric', string $lang = 'fr', string $result): string
	{
		$apiKey = $this->getApiKey();

		// Construire les paramètres de requête
		$query = [
			'appid' => $apiKey,
			'units' => $unit,
			'lang' => $lang,
		];

		if(preg_match('/^\d{5}$/', $result)) {
			$query['zip'] = $result;
		} else {
			$query['q'] = $result;
		}

		// Faire la requête API
		$response = $this->client->request(
			'GET',
			'https://api.openweathermap.org/data/2.5/weather',
			[
				'query' => $query,
			]
		);

		return $response->getContent();
	}

}
