<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/{_locale}', name: 'app_home', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
    #[Route('/', name: 'app_home_default')]
    public function index(ProjectRepository $projectRepository): Response
    {
        // Get featured projects from database
        $projects = $projectRepository->findFeatured(3);

        // Sample skills data
        $skills = [
            'frontend' => [
                ['name' => 'HTML5', 'logo' => 'html5.svg'],
                ['name' => 'CSS3', 'logo' => 'css3.svg'],
                ['name' => 'JavaScript', 'logo' => 'javascript.svg'],
                ['name' => 'TypeScript', 'logo' => 'typescript.svg'],
                ['name' => 'React', 'logo' => 'react.svg'],
                ['name' => 'Vue.js', 'logo' => 'vue.svg'],
                ['name' => 'Tailwind CSS', 'logo' => 'tailwind.svg']
            ],
            'backend' => [
                ['name' => 'PHP', 'logo' => 'php.svg'],
                ['name' => 'Symfony', 'logo' => 'symfony.svg'],
                ['name' => 'Node.js', 'logo' => 'nodejs.svg'],
                ['name' => 'Python', 'logo' => 'python.svg'],
                ['name' => 'MySQL', 'logo' => 'mysql.svg'],
                ['name' => 'PostgreSQL', 'logo' => 'postgresql.svg'],
                ['name' => 'MongoDB', 'logo' => 'mongodb.svg']
            ],
            'tools' => [
                ['name' => 'Git', 'logo' => 'git.svg'],
                ['name' => 'Docker', 'logo' => 'docker.svg'],
                ['name' => 'AWS', 'logo' => 'aws.svg'],
                ['name' => 'Linux', 'logo' => 'linux.svg'],
                ['name' => 'VS Code', 'logo' => 'vscode.svg'],
                ['name' => 'Figma', 'logo' => 'figma.svg']
            ]
        ];

        return $this->render('home/index.html.twig', [
            'projects' => $projects,
            'skills' => $skills,
        ]);
    }

    #[Route('/{_locale}/projects', name: 'app_projects', requirements: ['_locale' => 'fr|en'], defaults: ['_locale' => 'fr'])]
    public function projects(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAllPublished();
        
        return $this->render('home/projects.html.twig', [
            'projects' => $projects,
        ]);
    }
}
