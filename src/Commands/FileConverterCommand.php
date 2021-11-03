<?php

namespace App\Commands;

use App\FileHandler\FileHandlerFactory;
use App\Sheets\SheetsFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileConverterCommand extends Command
{
    protected static $defaultName = 'file-converter';

    public function __construct(
        protected FileHandlerFactory $fileHandler,
        protected SheetsFactory $sheetsFactory,
        protected LoggerInterface $logger,
    )
    {
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The path to the file.');
        $this->addArgument('destination', InputArgument::OPTIONAL, 'Where to upload file.', 'google_sheet');
        $this->addArgument('remote', InputArgument::OPTIONAL, 'The type to the file remote.', 'local');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $data = $this->fileHandler
                ->get($input->getArgument('remote'))
                ->setPath($input->getArgument('file'))
                ->getFileData();

            $sheetHandler = $this->sheetsFactory
                ->get($input->getArgument('destination'), $data);

            $response = $sheetHandler->sendData();

            $output->writeln("SheetId: {$response['sheet_id']}, Range: {$response['range']},  Updated Cells: {$response['updated_cells']}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error("Error: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}