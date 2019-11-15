<?php
declare(strict_types=1);

namespace Weather;

use RestClient;

/**
 * Class Weather
 * @package src
 */
class Weather
{
    /**
     * @param string $cityCommaCountry
     *
     * @return string
     */
    public static function getWeather(string $cityCommaCountry): string
    {
        $api = new RestClient([
            'base_url' => 'https://openweathermap.org/data/2.5',
        ]);

        $result = $api->get('find', [
            'q' => $cityCommaCountry,
            'units' => 'metric',
            'appid' => 'b6907d289e10d714a6e88b30761fae22',
        ]);
        if ($result->info->http_code == 200) {
            $response = json_decode($result->response, true)['list'][0] ?? [];
            $response['main']['temp'] = ($response['main']['temp'] > 200) ? $response['main']['temp'] - 273.15 : $response['main']['temp'];
            if (!empty($response) && !empty($response['name'])) {
                return "You asked weather for the $cityCommaCountry, founded next weather in {$response['name']} are next: {$response['main']['temp']}°C, {$response['weather'][0]['main']}" . PHP_EOL;
            }
        }

        return "For city: $cityCommaCountry -- no info about weather has been found!" . PHP_EOL;
    }
}
