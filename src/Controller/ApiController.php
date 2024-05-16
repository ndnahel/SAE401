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

class ApiController extends AbstractController
{
	/**
	 * @var Api
	 */
	private Api $api;
	
	/**
	 * @param Api $api
	 */
	public function __construct(Api $api) {
		$this->api = $api;
	}
	
	/**
	 * @param string $where
	 * @param string|null $unit
	 * @param string|null $lang
	 * @return Response
	 * @throws TransportExceptionInterface
	 * @throws ClientExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 */
	#[Route('/api/{endpoint}/{where}/{unit}/{lang}', name: 'api_weather')]
	public function apiRequest(
		string $endpoint,
		string $where,
		?string $unit = 'metric',
		?string $lang = 'fr'
	) : Response {
		/** @var User $user */
		$user = $this->getUser();

		$response = '';
		if ($endpoint === 'weather') {
			$response = $this->api->getWeather(
				$where,
				$user ? $user->getUnit() : $unit,
				$user ? $user->getLang() : $lang
			);
		} elseif ($endpoint === 'forcast') {
			$response = $this->api->getForecast(
				$where,
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
}
