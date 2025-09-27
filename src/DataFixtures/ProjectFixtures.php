<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\ProjectImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Projet 1 : Leekwars
        $project1 = new Project();
        $project1->setTitle('Leekwars');
        $project1->setTitleEn('Leekwars');
        $project1->setDescription('Participation au jeu de programmation Leekwars.<br>Développement d\'algorithmes d\'intelligence artificielle pour des combats automatisés.<br>Optimisation des stratégies de combat et gestion des ressources.<br>Classement dans le top des joueurs grâce à des algorithmes avancés.');
        $project1->setDescriptionEn('Participation in the Leekwars programming game.<br>Development of artificial intelligence algorithms for automated battles.<br>Optimization of combat strategies and resource management.<br>Ranking in the top players thanks to advanced algorithms.');
        $project1->setTechnologies(['JavaScript', 'Algorithmes', 'IA', 'Optimisation', 'Logique']);
        $project1->setGithubUrl('https://github.com/VincentFerry/leekwars-scripts');
        $project1->setDemoUrl('https://leekwars.com/farmer/VincentFerry');
        $project1->setFeatured(true);
        $project1->setPublished(true);
        $project1->setCreatedAt(new \DateTimeImmutable('2022-03-01'));
        $project1->setUpdatedAt(new \DateTimeImmutable('2022-03-01'));

        // Images pour le projet 1
        $image1_1 = new ProjectImage();
        $image1_1->setFilename('leekwars-1.jpg');
        $image1_1->setImagePath('/images/projects/leekwars-1.jpg');
        $image1_1->setAlt('Interface de combat Leekwars');
        $image1_1->setPrimary(true);
        $image1_1->setProject($project1);

        $manager->persist($project1);
        $manager->persist($image1_1);

        // Projet 2 : Portfolio
        $project2 = new Project();
        $project2->setTitle('Portfolio');
        $project2->setTitleEn('Portfolio');
        $project2->setDescription('Développement de mon portfolio personnel avec Symfony.<br>Design moderne et responsive avec Tailwind CSS.<br>Système multilingue français/anglais.<br>Interface d\'administration pour gérer les projets.<br>Optimisé pour le SEO et les performances.');
        $project2->setDescriptionEn('Development of my personal portfolio with Symfony.<br>Modern and responsive design with Tailwind CSS.<br>French/English multilingual system.<br>Administration interface to manage projects.<br>Optimized for SEO and performance.');
        $project2->setTechnologies(['PHP', 'Symfony', 'Twig', 'JavaScript', 'HTML5/CSS3', 'Tailwind CSS', 'MySQL', 'Git']);
        $project2->setGithubUrl('https://github.com/VincentFerry/portfolio');
        $project2->setDemoUrl('https://vincentferry.fr');
        $project2->setFeatured(true);
        $project2->setPublished(true);
        $project2->setCreatedAt(new \DateTimeImmutable('2024-12-01'));
        $project2->setUpdatedAt(new \DateTimeImmutable('2024-12-01'));

        // Images pour le projet 2
        $image2_1 = new ProjectImage();
        $image2_1->setFilename('portfolio-1.jpg');
        $image2_1->setImagePath('/images/projects/portfolio-1.jpg');
        $image2_1->setAlt('Page d\'accueil du portfolio');
        $image2_1->setPrimary(true);
        $image2_1->setProject($project2);

        $manager->persist($project2);
        $manager->persist($image2_1);

        // Projet 3 : Miroir connecté
        $project3 = new Project();
        $project3->setTitle('Mirroir connecté');
        $project3->setTitleEn('Connected Mirror');
        $project3->setDescription('Configuration d\'un raspberry pi pour un miroir connecté.<br>Création d\'un module pour suivre les informations de mon club de volley (calendrier, résultats, classement, news, etc.)<br>Script bash pour gérer l\'interruption WiFi des leds.<br>Affichage de galeries photo avec Google photos.<br>Et bien d\'autres modules de bases (horloge, météo, news, etc.)');
        $project3->setDescriptionEn('Configuration of a raspberry pi for a connected mirror.<br>Creation of a module to follow my volleyball club information (calendar, results, ranking, news, etc.)<br>Bash script to manage WiFi interruption of LEDs.<br>Display of photo galleries with Google photos.<br>And many other basic modules (clock, weather, news, etc.)');
        $project3->setTechnologies(['HTML/CSS', 'JavaScript', 'Node.js', 'Bash/Shell', 'Raspberry Pi', 'WebSockets', 'Electron', 'PHP', 'CURL/APIs']);
        $project3->setGithubUrl('https://github.com/VincentFerry/miroir-connecte');
        $project3->setDemoUrl(null);
        $project3->setFeatured(true);
        $project3->setPublished(true);
        $project3->setCreatedAt(new \DateTimeImmutable('2023-01-15'));
        $project3->setUpdatedAt(new \DateTimeImmutable('2023-01-15'));

        // Images pour le projet 3
        $image3_1 = new ProjectImage();
        $image3_1->setFilename('miroir-1.jpg');
        $image3_1->setImagePath('/images/projects/miroir-1.jpg');
        $image3_1->setAlt('Interface du miroir connecté');
        $image3_1->setPrimary(true);
        $image3_1->setProject($project3);

        $manager->persist($project3);
        $manager->persist($image3_1);

        $manager->flush();
    }
}
