<?php

namespace App\EventListener;

use App\Entity\ProjectImage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: ProjectImage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: ProjectImage::class)]
class ProjectImageListener
{
    private ?string $filenameToDelete = null;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] private string $projectDir
    ) {}

    public function preRemove(ProjectImage $projectImage, LifecycleEventArgs $event): void
    {
        // Stocker le nom du fichier avant suppression de l'entité
        $this->filenameToDelete = $projectImage->getFilename();
    }

    public function postRemove(ProjectImage $projectImage, LifecycleEventArgs $event): void
    {
        if ($this->filenameToDelete) {
            $this->deleteImageFile($this->filenameToDelete);
            $this->filenameToDelete = null;
        }
    }

    private function deleteImageFile(string $filename): void
    {
        $filePath = $this->projectDir . '/public/images/projects/' . $filename;
        
        if (file_exists($filePath)) {
            try {
                unlink($filePath);
                error_log("Image supprimée avec succès : " . $filename);
            } catch (\Exception $e) {
                error_log("Erreur lors de la suppression de l'image " . $filename . " : " . $e->getMessage());
            }
        } else {
            error_log("Fichier image non trouvé pour suppression : " . $filePath);
        }
    }
}
