<?php

namespace App\Command;

use App\Repository\ProjectImageRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:clean-orphan-images',
    description: 'Nettoie les images orphelines (fichiers sans entrée en base de données)',
)]
class CleanOrphanImagesCommand extends Command
{
    public function __construct(
        private ProjectImageRepository $projectImageRepository,
        #[Autowire('%kernel.project_dir%')] private string $projectDir
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Afficher les fichiers qui seraient supprimés sans les supprimer')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Supprimer les fichiers sans confirmation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');
        $force = $input->getOption('force');

        $imagesDir = $this->projectDir . '/public/images/projects';
        
        if (!is_dir($imagesDir)) {
            $io->error("Le répertoire d'images n'existe pas : $imagesDir");
            return Command::FAILURE;
        }

        // Récupérer tous les noms de fichiers en base
        $dbFilenames = $this->projectImageRepository->getAllFilenames();
        
        // Lister tous les fichiers dans le répertoire
        $files = array_diff(scandir($imagesDir), ['.', '..', '.gitkeep']);
        
        $orphanFiles = [];
        $totalSize = 0;
        
        foreach ($files as $file) {
            if (!in_array($file, $dbFilenames)) {
                $filePath = $imagesDir . '/' . $file;
                $fileSize = filesize($filePath);
                $orphanFiles[] = [
                    'name' => $file,
                    'path' => $filePath,
                    'size' => $fileSize,
                    'formatted_size' => $this->formatBytes($fileSize)
                ];
                $totalSize += $fileSize;
            }
        }

        if (empty($orphanFiles)) {
            $io->success('Aucun fichier orphelin trouvé !');
            return Command::SUCCESS;
        }

        $io->section('Fichiers orphelins détectés :');
        
        $tableData = [];
        foreach ($orphanFiles as $file) {
            $tableData[] = [$file['name'], $file['formatted_size']];
        }
        
        $io->table(['Fichier', 'Taille'], $tableData);
        $io->text("Total : " . count($orphanFiles) . " fichiers (" . $this->formatBytes($totalSize) . ")");

        if ($dryRun) {
            $io->note('Mode dry-run : aucun fichier n\'a été supprimé');
            return Command::SUCCESS;
        }

        if (!$force && !$io->confirm('Voulez-vous supprimer ces fichiers orphelins ?', false)) {
            $io->text('Opération annulée');
            return Command::SUCCESS;
        }

        $deletedCount = 0;
        $errors = [];

        foreach ($orphanFiles as $file) {
            try {
                if (unlink($file['path'])) {
                    $deletedCount++;
                    $io->text("✓ Supprimé : " . $file['name']);
                } else {
                    $errors[] = "Impossible de supprimer : " . $file['name'];
                }
            } catch (\Exception $e) {
                $errors[] = "Erreur lors de la suppression de " . $file['name'] . " : " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $io->warning('Erreurs rencontrées :');
            foreach ($errors as $error) {
                $io->text("✗ $error");
            }
        }

        $io->success("$deletedCount fichiers orphelins supprimés avec succès !");

        return Command::SUCCESS;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
