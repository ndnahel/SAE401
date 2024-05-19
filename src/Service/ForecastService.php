<?php
namespace App\Service;

class ForecastService {
    private $api;

    public function __construct(Api $api) {
        $this->api = $api;
    }

    public function getForecastData($location, $unit, $lang) {
        $forecast = $this->api->getForecast($location, $unit, $lang);
        $forecast = json_decode($forecast, true);

        return $forecast;
    }

    public function formatForecastData($forecast) {
        $forecastList = [];
        $now = (new \DateTime())->format('Y-m-d');

        foreach ($forecast['list'] as $forecast) {
            $_date = new \DateTime($forecast['dt_txt']);
            $date = $_date->format('Y-m-d');
            $hour = $_date->format('H:i:s');

            if ($now < $date && $hour == '12:00:00') {
                $forecastList[] = $forecast;
            }
        }

        return $forecastList;
    }
}