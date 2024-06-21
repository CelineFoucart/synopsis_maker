<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\LogService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

final class BackupService
{
    private ?string $filename = null;

    public function __construct(
        private $tmpDir, 
        private Filesystem $filesystem, 
        private LogService $logService,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Set the value of filename.
     */
    public function setFilename(): self
    {
        $date = new DateTime();
        $this->filename = 'backup-'.$date->format('Y-m-d-H-i-s').'.sql';

        return $this;
    }

    /**
     * Get the value of filename.
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function getFilePath(): string
    {
        return $this->tmpDir . DIRECTORY_SEPARATOR . $this->filename;
    }

    public function exportData(): bool
    {
        try {
            $this->setFilename();            
            $dump = shell_exec($this->getCommand());
            $this->filesystem->dumpFile($this->getFilePath(), $dump);
            $this->logService->info("Backup", "Database has been successfully exported.", "BackupController");

            return true;
        } catch (\Exception $th) {
            $className = (new \ReflectionClass($th))->getShortName();
            $this->logService->error('Backup', '#message: ' . $th->getMessage(), $className, $th->getTraceAsString());

            return false;
        }
    }

    private function getCommand(): string
    {
        $dbparams = $this->entityManager->getConnection()->getParams();

        return 'mysqldump --user='.$dbparams['user'].' --password="'.$dbparams['password'].'" --databases '.$dbparams['dbname'];
    }
}
