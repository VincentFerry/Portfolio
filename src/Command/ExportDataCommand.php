<?php

namespace App\Command;

use App\Repository\ProjectRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export-data',
    description: 'Export current database data to fixtures format',
)]
class ExportDataCommand extends Command
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $projects = $this->projectRepository->findAll();
        
        if (empty($projects)) {
            $io->warning('No projects found in database');
            return Command::SUCCESS;
        }

        $io->title('Exporting Projects Data');
        
        $fixtureCode = $this->generateFixtureCode($projects);
        
        // Sauvegarder dans un fichier
        $filename = 'exported_fixtures_' . date('Y-m-d_H-i-s') . '.php';
        file_put_contents($filename, $fixtureCode);
        
        $io->success("Data exported to: $filename");
        $io->note('Copy this content to src/DataFixtures/ProjectFixtures.php');
        
        // Afficher aussi le code généré
        $io->section('Generated Fixture Code:');
        $output->writeln($fixtureCode);
        
        return Command::SUCCESS;
    }

    private function generateFixtureCode(array $projects): string
    {
        $code = "<?php\n\nnamespace App\\DataFixtures;\n\n";
        $code .= "use App\\Entity\\Project;\n";
        $code .= "use App\\Entity\\ProjectImage;\n";
        $code .= "use Doctrine\\Bundle\\FixturesBundle\\Fixture;\n";
        $code .= "use Doctrine\\Persistence\\ObjectManager;\n\n";
        $code .= "class ProjectFixtures extends Fixture\n{\n";
        $code .= "    public function load(ObjectManager \$manager): void\n    {\n";

        foreach ($projects as $index => $project) {
            $projectVar = '$project' . ($index + 1);
            
            $code .= "\n        // Projet " . ($index + 1) . " : " . $project->getTitle() . "\n";
            $code .= "        $projectVar = new Project();\n";
            $code .= "        $projectVar->setTitle('" . addslashes($project->getTitle()) . "');\n";
            
            if ($project->getTitleEn()) {
                $code .= "        $projectVar->setTitleEn('" . addslashes($project->getTitleEn()) . "');\n";
            }
            
            $code .= "        $projectVar->setDescription('" . addslashes($project->getDescription()) . "');\n";
            
            if ($project->getDescriptionEn()) {
                $code .= "        $projectVar->setDescriptionEn('" . addslashes($project->getDescriptionEn()) . "');\n";
            }
            
            $technologies = json_encode($project->getTechnologies());
            $code .= "        $projectVar->setTechnologies($technologies);\n";
            
            if ($project->getGithubUrl()) {
                $code .= "        $projectVar->setGithubUrl('" . $project->getGithubUrl() . "');\n";
            }
            
            if ($project->getDemoUrl()) {
                $code .= "        $projectVar->setDemoUrl('" . $project->getDemoUrl() . "');\n";
            }
            
            $code .= "        $projectVar->setFeatured(" . ($project->isFeatured() ? 'true' : 'false') . ");\n";
            $code .= "        $projectVar->setPublished(" . ($project->isPublished() ? 'true' : 'false') . ");\n";
            
            if ($project->getCreatedAt()) {
                $createdAt = $project->getCreatedAt()->format('Y-m-d H:i:s');
                $code .= "        $projectVar->setCreatedAt(new \\DateTimeImmutable('$createdAt'));\n";
            }
            
            if ($project->getUpdatedAt()) {
                $updatedAt = $project->getUpdatedAt()->format('Y-m-d H:i:s');
                $code .= "        $projectVar->setUpdatedAt(new \\DateTimeImmutable('$updatedAt'));\n";
            }
            
            $code .= "\n        \$manager->persist($projectVar);\n";
            
            // Ajouter les images si elles existent
            if ($project->getImages()->count() > 0) {
                foreach ($project->getImages() as $imgIndex => $image) {
                    $imageVar = '$image' . ($index + 1) . '_' . ($imgIndex + 1);
                    
                    $code .= "\n        // Image " . ($imgIndex + 1) . " pour le projet " . ($index + 1) . "\n";
                    $code .= "        $imageVar = new ProjectImage();\n";
                    $code .= "        $imageVar->setFilename('" . addslashes($image->getFilename()) . "');\n";
                    $code .= "        $imageVar->setImagePath('" . addslashes($image->getImagePath()) . "');\n";
                    
                    if ($image->getAlt()) {
                        $code .= "        $imageVar->setAlt('" . addslashes($image->getAlt()) . "');\n";
                    }
                    
                    $code .= "        $imageVar->setPrimary(" . ($image->isPrimary() ? 'true' : 'false') . ");\n";
                    $code .= "        $imageVar->setSortOrder(" . $image->getSortOrder() . ");\n";
                    $code .= "        $imageVar->setProject($projectVar);\n";
                    $code .= "        \$manager->persist($imageVar);\n";
                }
            }
        }
        
        $code .= "\n        \$manager->flush();\n";
        $code .= "    }\n}\n";
        
        return $code;
    }
}
