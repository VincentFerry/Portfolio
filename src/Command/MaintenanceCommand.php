<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:maintenance',
    description: 'Gère le mode maintenance du site',
)]
class MaintenanceCommand extends Command
{
    private string $projectDir;
    private string $maintenanceFile;
    private string $htaccessFile;
    private string $htaccessBackup;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();
        $this->projectDir = $parameterBag->get('kernel.project_dir');
        $this->maintenanceFile = $this->projectDir . '/public/.maintenance';
        $this->htaccessFile = $this->projectDir . '/public/.htaccess';
        $this->htaccessBackup = $this->projectDir . '/public/.htaccess.backup';
    }

    protected function configure(): void
    {
        $this
            ->addArgument('action', InputArgument::REQUIRED, 'Action à effectuer (on, off, status)')
            ->setHelp('Cette commande permet de gérer le mode maintenance du site.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');

        switch ($action) {
            case 'on':
                return $this->enableMaintenance($io);
            case 'off':
                return $this->disableMaintenance($io);
            case 'status':
                return $this->getStatus($io);
            default:
                $io->error("Action invalide. Utilisez 'on', 'off' ou 'status'.");
                return Command::FAILURE;
        }
    }

    private function enableMaintenance(SymfonyStyle $io): int
    {
        try {
            // Créer le fichier de maintenance
            file_put_contents($this->maintenanceFile, date('Y-m-d H:i:s'));

            // Sauvegarder le .htaccess actuel
            if (file_exists($this->htaccessFile)) {
                copy($this->htaccessFile, $this->htaccessBackup);
                $io->info('Sauvegarde du .htaccess actuel effectuée');
            }

            // Créer le .htaccess de maintenance
            $htaccessContent = $this->getMaintenanceHtaccess();
            file_put_contents($this->htaccessFile, $htaccessContent);

            $io->success('Mode maintenance ACTIVÉ');
            $io->note('Le site redirige maintenant vers /maintenance.html');
            $io->note('Les assets (images, CSS, JS) restent accessibles');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Erreur lors de l\'activation de la maintenance: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function disableMaintenance(SymfonyStyle $io): int
    {
        try {
            // Supprimer le fichier de maintenance
            if (file_exists($this->maintenanceFile)) {
                unlink($this->maintenanceFile);
                $io->info('Fichier de maintenance supprimé');
            }

            // Restaurer le .htaccess original
            if (file_exists($this->htaccessBackup)) {
                rename($this->htaccessBackup, $this->htaccessFile);
                $io->info('.htaccess original restauré');
            } else {
                // Supprimer le .htaccess de maintenance s'il n'y a pas de sauvegarde
                if (file_exists($this->htaccessFile)) {
                    unlink($this->htaccessFile);
                    $io->warning('Aucune sauvegarde .htaccess trouvée - fichier supprimé');
                }
            }

            $io->success('Mode maintenance DÉSACTIVÉ');
            $io->note('Le site est de nouveau accessible normalement');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Erreur lors de la désactivation de la maintenance: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function getStatus(SymfonyStyle $io): int
    {
        if (file_exists($this->maintenanceFile)) {
            $timestamp = file_get_contents($this->maintenanceFile);
            $io->warning('Mode maintenance ACTIF');
            $io->table(
                ['Information', 'Valeur'],
                [
                    ['Activé depuis', $timestamp],
                    ['Page de maintenance', '/maintenance.html'],
                    ['Fichier de contrôle', $this->maintenanceFile],
                ]
            );
        } else {
            $io->success('Mode maintenance INACTIF');
            $io->note('Le site est accessible normalement');
        }

        return Command::SUCCESS;
    }

    private function getMaintenanceHtaccess(): string
    {
        $date = date('Y-m-d H:i:s');
        return <<<HTACCESS
# Mode maintenance activé le $date
# Généré automatiquement par la commande app:maintenance

RewriteEngine On

# Permettre l'accès aux assets nécessaires
RewriteCond %{REQUEST_URI} !^/maintenance\.html$
RewriteCond %{REQUEST_URI} !^/images/
RewriteCond %{REQUEST_URI} !^/build/
RewriteCond %{REQUEST_URI} !^/bundles/
RewriteCond %{REQUEST_URI} !^/favicon\.ico$

# Rediriger tout le reste vers la page de maintenance
RewriteRule ^(.*)$ /maintenance.html [R=503,L]

# Headers pour indiquer la maintenance temporaire
Header always set Retry-After "300"
Header always set Cache-Control "no-cache, no-store, must-revalidate"

HTACCESS;
    }
}
