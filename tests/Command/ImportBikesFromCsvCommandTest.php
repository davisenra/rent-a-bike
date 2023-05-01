<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\ImportBikesFromCsvCommand;
use App\Entity\Bike;
use App\Repository\BikeRepository;
use App\Tests\Database\DatabaseDependantTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ImportBikesFromCsvCommandTest extends DatabaseDependantTestCase
{
    private readonly Command $importBikesFromCsvCommand;
    private readonly BikeRepository $bikeRepository;

    protected function setUp(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->bikeRepository = new BikeRepository($entityManager);
        $this->importBikesFromCsvCommand = new ImportBikesFromCsvCommand($this->bikeRepository);

        parent::setUp();
    }

    public function testItBreaksIfFileNotFound(): void
    {
        $commandTester = new CommandTester($this->importBikesFromCsvCommand);
        $commandTester->execute([
            'file' => 'non-existent-file.csv',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('File not found.', $output);
    }

    public function testItSaveBikesToDatabase(): void
    {
        $filepath = __DIR__ . '/../../var/bikes.csv';

        $file = fopen($filepath, 'w');
        fputcsv($file, ['MODEL', 'IS_AVAILABLE', 'PRICE_PER_MINUTE']);
        fputcsv($file, ['Specialized Rockhopper', '1', '1.50']);
        fputcsv($file, ['Specialized Trailblazer', '1', '2.50']);
        fclose($file);

        $commandTester = new CommandTester($this->importBikesFromCsvCommand);
        $commandTester->execute([
            'file' => $filepath,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Bikes imported successfully.', $output);

        $availableBikes = $this->bikeRepository->allAvailableBikes();

        $this->assertCount(2, $availableBikes);
        $this->assertInstanceOf(Bike::class, $availableBikes[0]);

        unlink($filepath); // Remove file after test
    }
}
