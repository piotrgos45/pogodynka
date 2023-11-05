<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{cityName}', name: 'app_weather')]
    public function city(string $cityName, LocationRepository $locationRepository, MeasurementRepository $measurementRepository): Response
    {
        $location = $locationRepository->findOneByCity($cityName);

        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }

        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

}
