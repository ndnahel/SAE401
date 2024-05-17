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
    public function __construct(Api $api)
    {
        $this->api = $api;
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
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $response = $this->api->getWeather(
            $city,
            $user ? $user->getUnit() : $unit,
            $user ? $user->getLang() : $lang
        );

        return new Response(
            $response,
            200,
            ['content-type' => 'application/json']
        );
    }
}
