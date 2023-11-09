<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\WeatherUtil;

#[AsCommand(
    name: 'weather:location',
    description: 'Returns temperature from forecasts for a city by passed id',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(private readonly WeatherUtil $weatherUtil)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL, 'City id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $locationRepository = $this->weatherUtil->getLocationRepository();
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('id');
        $location = $locationRepository->find($locationId);

        $measurements = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s', $location->getCity()));

        foreach ($measurements as $measurement){
            $io->writeln(sprintf("\t%s: %s",
            $measurement->getDate()->format("Y-m-d"),
            $measurement->getCelsius()
            ));
        }

        return Command::SUCCESS;
    }
}
