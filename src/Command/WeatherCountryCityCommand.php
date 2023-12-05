<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class WeatherCountryCityCommand extends Command
{
    protected static $defaultName = 'weather:country-city';

    private $locationRepository;
    private $weatherUtil;

    public function __construct(LocationRepository $locationRepository, WeatherUtil $weatherUtil)
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
        $this->weatherUtil = $weatherUtil;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Get location based on country code and city name')
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code')
            ->addArgument('cityName', InputArgument::REQUIRED, 'City name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');

        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $cityName,
        ]);

        if (!$location) {
            $io->error('Location not found');
            return Command::FAILURE;
        }

        $io->success(sprintf('Found location: %s, %s', $location->getCity(), $location->getCountry()));

        return Command::SUCCESS;
    }
}