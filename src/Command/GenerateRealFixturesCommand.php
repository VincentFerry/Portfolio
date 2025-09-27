<?php

namespace App\Command;

use App\Repository\ProjectRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-real-fixtures',
    description: 'Generate fixtures from current database data',
)]
class GenerateRealFixturesCommand extends Command
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
            $io->error('No projects found in database');
            return Command::FAILURE;
        }

        $io->title('Generating Real Fixtures from Database');
        
        $fixtureContent = $this->generateFixtureContent($projects);
        
        // Écrire dans le fichier fixtures
        $fixturesPath = 'src/DataFixtures/ProjectFixtures.php';
        file_put_contents($fixturesPath, $fixtureContent);
        
        $io->success("Real fixtures generated in: $fixturesPath");
        $io->note('You can now deploy these fixtures to production');
        
        // Afficher un résumé
        $io->section('Projects found:');
        foreach ($projects as $project) {
            $io->writeln("- {$project->getTitle()} (ID: {$project->getId()})");
        }
        
        return Command::SUCCESS;
    }

    private function generateFixtureContent(array $projects): string
    {
        $content = "<?php\n\nnamespace App\\DataFixtures;\n\n";
        $content .= "use App\\Entity\\Project;\n";
        $content .= "use App\\Entity\\ProjectImage;\n";
        $content .= "use Doctrine\\Bundle\\FixturesBundle\\Fixture;\n";
        $content .= "use Doctrine\\Persistence\\ObjectManager;\n\n";
        $content .= "class ProjectFixtures extends Fixture\n{\n";
        $content .= "    public function load(ObjectManager \$manager): void\n    {\n";

        foreach ($projects as $index => $project) {
            $projectVar = '$project' . ($index + 1);
            
            $content .= "\n        // Projet " . ($index + 1) . " : " . $project->getTitle() . "\n";
            $content .= "        $projectVar = new Project();\n";
            $content .= "        $projectVar->setTitle('" . $this->escapeString($project->getTitle()) . "');\n";
            
            if ($project->getTitleEn()) {
                $content .= "        $projectVar->setTitleEn('" . $this->escapeString($project->getTitleEn()) . "');\n";
            }
            
            $content .= "        $projectVar->setDescription('" . $this->escapeString($project->getDescription()) . "');\n";
            
            if ($project->getDescriptionEn()) {
                $content .= "        $projectVar->setDescriptionEn('" . $this->escapeString($project->getDescriptionEn()) . "');\n";
            }
            
            // Technologies
            $technologies = var_export($project->getTechnologies(), true);
            $content .= "        $projectVar->setTechnologies($technologies);\n";
            
            if ($project->getGithubUrl()) {
                $content .= "        $projectVar->setGithubUrl('" . $project->getGithubUrl() . "');\n";
            } else {
                $content .= "        $projectVar->setGithubUrl(null);\n";
            }
            
            if ($project->getDemoUrl()) {
                $content .= "        $projectVar->setDemoUrl('" . $project->getDemoUrl() . "');\n";
            } else {
                $content .= "        $projectVar->setDemoUrl(null);\n";
            }
            
            $content .= "        $projectVar->setFeatured(" . ($project->isFeatured() ? 'true' : 'false') . ");\n";
            $content .= "        $projectVar->setPublished(" . ($project->isPublished() ? 'true' : 'false') . ");\n";
            
            if ($project->getCreatedAt()) {
                $createdAt = $project->getCreatedAt()->format('Y-m-d H:i:s');
                $content .= "        $projectVar->setCreatedAt(new \\DateTimeImmutable('$createdAt'));\n";
            }
            
            if ($project->getUpdatedAt()) {
                $updatedAt = $project->getUpdatedAt()->format('Y-m-d H:i:s');
                $content .= "        $projectVar->setUpdatedAt(new \\DateTimeImmutable('$updatedAt'));\n";
            }
            
            $content .= "\n        \$manager->persist($projectVar);\n";
            
            // Ajouter les images
            if ($project->getImages() && $project->getImages()->count() > 0) {
                foreach ($project->getImages() as $imgIndex => $image) {
                    $imageVar = '$image' . ($index + 1) . '_' . ($imgIndex + 1);
                    
                    $content .= "\n        // Image " . ($imgIndex + 1) . " pour " . $project->getTitle() . "\n";
                    $content .= "        $imageVar = new ProjectImage();\n";
                    $content .= "        $imageVar->setFilename('" . $this->escapeString($image->getFilename()) . "');\n";
                    
                    if (method_exists($image, 'getImagePath') && $image->getImagePath()) {
                        $content .= "        $imageVar->setImagePath('" . $this->escapeString($image->getImagePath()) . "');\n";
                    }
                    
                    if ($image->getAlt()) {
                        $content .= "        $imageVar->setAlt('" . $this->escapeString($image->getAlt()) . "');\n";
                    }
                    
                    $content .= "        $imageVar->setPrimary(" . ($image->isPrimary() ? 'true' : 'false') . ");\n";
                    
                    if (method_exists($image, 'getSortOrder')) {
                        $content .= "        $imageVar->setSortOrder(" . $image->getSortOrder() . ");\n";
                    }
                    
                    $content .= "        $imageVar->setProject($projectVar);\n";
                    $content .= "        \$manager->persist($imageVar);\n";
                }
            }
        }
        
        $content .= "\n        \$manager->flush();\n";
        $content .= "    }\n\n";
        $content .= "    private function escapeString(string \$str): string\n";
        $content .= "    {\n";
        $content .= "        return addslashes(\$str);\n";
        $content .= "    }\n";
        $content .= "}\n";
        
        return $content;
    }

    private function escapeString(?string $str): string
    {
        if ($str === null) {
            return '';
        }
        return addslashes($str);
    }
}
