<?php
namespace App\Service;

class WindDirection {
    public function getCompasPoint(int $deg) : string
    {
        switch ($deg) {
            case ($deg > 10.5 && $deg <= 30.5): return 'N/Nord-Est';
            case ($deg > 30.5 && $deg <= 60.5): return 'Nord-Est';
            case ($deg > 60.5 && $deg <= 80.5): return 'E/Nord-Est';
            case ($deg > 80.5 && $deg <= 100.5): return 'Est';
            case ($deg > 100.5 && $deg <= 130.5): return 'E/Sud-Est';
            case ($deg > 130.5 && $deg <= 150.5): return 'Sus-Est';
            case ($deg > 150.5 && $deg <= 180.5): return 'S/Sus-Est';
            case ($deg > 180.5 && $deg <= 200.5): return 'Sud';
            case ($deg > 200.5 && $deg <= 230.5): return 'S/Sud-Ouest';
            case ($deg > 230.5 && $deg <= 250.5): return 'Sud-Ouest';
            case ($deg > 250.5 && $deg <= 280.5): return 'O/Sud-Ouest';
            case ($deg > 280.5 && $deg <= 300.5): return 'Ouest';
            case ($deg > 300.5 && $deg <= 330.5): return 'O/Nord-Ouest';
            case ($deg > 330.5 && $deg <= 350.5): return 'Nord-Ouest';
            default: return 'Nord';
        }        
    }

    public function addCompasPoint(array $data) {
        $data['wind']['direction'] = $this->getCompasPoint($data['wind']['deg']);
        return $data;
    }
}