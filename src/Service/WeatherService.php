<?php
namespace App\Service;
use App\Service\Api;
use App\Service\WindDirection;

class WeatherService {
    private $api;
    private $windDirection;

    public function __construct(Api $api, WindDirection $windDirection) {
        $this->api = $api;
        $this->windDirection = $windDirection;
    }

    public function getWeatherData($location, $unit, $lang) {
        $weather = $this->api->getWeather($location, $unit, $lang);
        $weather = json_decode($weather, true);
        $weather = $this->windDirection->addCompasPoint($weather);

        return $weather;
    }
}