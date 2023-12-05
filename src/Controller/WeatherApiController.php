<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    private $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string    $city,
        #[MapQueryParameter('format')] string  $format = 'json',
        #[MapQueryParameter('twig')] bool      $twig = false,
    ): Response
    {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            $template = $format === 'csv' ? 'weather_api/index.csv.twig' : 'weather_api/index.json.twig';
            return $this->render($template, [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

        if ($format === 'csv') {
            $csvData = array_map(fn(Measurement $m) => sprintf(
                "%s,%s,%s,%s",
                $city,
                $country,
                $m->getDate()->format('Y-m-d'),
                $m->getCelsius()
            ), $measurements);

            $csvOutput = implode("\n", $csvData);
            return new Response($csvOutput, 200, ['Content-Type' => 'text/csv']);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
            ], $measurements),
        ]);
    }
}