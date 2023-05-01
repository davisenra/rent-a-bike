<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Bike;
use App\Repository\BikeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-bikes-from-csv',
    description: 'Import bikes from a .csv file to the database.'
)]
class ImportBikesFromCsvCommand extends Command
{
    public function __construct(private readonly BikeRepository $bikeRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The .csv file to be imported.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filepath = $input->getArgument('file');

        if (!file_exists($filepath)) {
            $io->error('File not found.');

            return Command::FAILURE;
        }

        $file = fopen($filepath, 'r');
        fgetcsv($file); // Skip headers

        $bikesData = [];
        while (($data = fgetcsv($file)) !== false) {
            $bikesData[] = [
                'bike_model' => $data[0],
                'is_bike_available' => (bool) $data[1],
                'price_per_minute' => $data[2],
            ];
        }

        fclose($file);
        $bikesFound = count($bikesData);

        $io->progressStart($bikesFound - 1);

        foreach ($bikesData as $bikeData) {
            $bike = new Bike();
            $bike->setModel($bikeData['bike_model']);
            $bike->setIsAvailable($bikeData['is_bike_available']);
            $bike->setPricePerMinute($bikeData['price_per_minute']);
            $bike->setCreatedAt(new \DateTimeImmutable());
            $bike->setUpdatedAt(new \DateTimeImmutable());

            $this->bikeRepository->save($bike);

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success("{$bikesFound} Bikes imported successfully.");

        return Command::SUCCESS;
    }
}
