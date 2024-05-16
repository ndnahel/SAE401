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
	public function requestApi(string $endpoint, string $result, string $unit = 'metric', string $lang = 'fr'): string
	{
		$apiKey = $this->getApiKey();

		$query = [
			'units' => $unit,
			'lang' => $lang,
			'appid' => $apiKey,
		];

		if(preg_match('/^\d{5}$/', $result)) {
			$query['zip'] = "$result,$lang";
		} else {
			$query['q'] = $result;
		}

		$response = $this->client->request(
			'GET',
			'https://api.openweathermap.org/data/2.5/' . $endpoint,
			[
				'query' => $query,
			]
		);

		return $response->getContent();
	}

	public function getWeather(string $result, string $unit = 'metric', string $lang = 'fr'): string
	{
		return $this->requestApi('weather', $result, $unit, $lang);
	}

	public function getForecast(string $result, string $unit = 'metric', string $lang = 'fr'): string
	{
		return $this->requestApi('forecast', $result, $unit, $lang);
	}
}
