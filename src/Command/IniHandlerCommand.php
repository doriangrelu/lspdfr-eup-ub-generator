<?php

namespace App\Command;

use App\Core\Adapter\UBAdapter;
use App\Core\IniParser;
use App\Core\IO\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IniHandlerCommand extends Command
{
    protected static $defaultName = 'app:ini-handler';
    /**
     * @var string
     */
    private $storageDirectory;

    public function __construct(string $name = null, string $storageDirectory)
    {
        parent::__construct($name);
        $this->storageDirectory = $storageDirectory;
    }

    protected function configure()
    {
        $this
            ->setDescription('XML Processing handler');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $files = glob($this->storageDirectory . '/*.tmp');
        $parser = new IniParser();

        $counter = 0;
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $xmlIo = new File($this->storageDirectory . '/' . $filename . '.xml');
            if ($xmlIo->fileExists()) {
                continue;
            }
            $fileIo = new File($file);
            try {
                $wardrobe = $parser->getWardrobeEntities($fileIo);
                $ub = new UBAdapter();
                $xml = $ub->parse($wardrobe);
                $xmlIo->createFileIfNotExists();
                $xmlIo->setContent($xml);
                $counter++;
            } catch (\Exception $e) {
                $io->error($e->getMessage());
            }
        }
        $io->success("Files processed with success: $counter");
        return 0;
    }
}
