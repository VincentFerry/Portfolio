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
        $project1->setCreatedAt(new \DateTime('2022-03-01'));
        $project1->setUpdatedAt(new \DateTime('2022-03-01'));

        // Images pour le projet 1 - Leekwars
        $image1_1 = new ProjectImage();
        $image1_1->setFilename('7d371ff0-8416-4631-933b-2cb6f1d9eb37.webp');
        $image1_1->setAlt('Interface de combat Leekwars');
        $image1_1->setIsPrimary(true);
        $image1_1->setSortOrder(1);
        $image1_1->setProject($project1);

        $image1_2 = new ProjectImage();
        $image1_2->setFilename('5465a17e-cae6-419f-9264-f99049a6c173.png');
        $image1_2->setAlt('Algorithmes Leekwars');
        $image1_2->setIsPrimary(false);
        $image1_2->setSortOrder(2);
        $image1_2->setProject($project1);

        $image1_3 = new ProjectImage();
        $image1_3->setFilename('4ffe7213-1aee-497c-9890-70af983ea6d0.png');
        $image1_3->setAlt('Stratégies de combat');
        $image1_3->setIsPrimary(false);
        $image1_3->setSortOrder(3);
        $image1_3->setProject($project1);

        $image1_4 = new ProjectImage();
        $image1_4->setFilename('6f6b75b2-ce2d-476b-b34f-f736892c1cb5.png');
        $image1_4->setAlt('Configuration Leekwars');
        $image1_4->setIsPrimary(false);
        $image1_4->setSortOrder(4);
        $image1_4->setProject($project1);

        $manager->persist($project1);
        $manager->persist($image1_1);
        $manager->persist($image1_2);
        $manager->persist($image1_3);
        $manager->persist($image1_4);

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
        $project2->setCreatedAt(new \DateTime('2024-12-01'));
        $project2->setUpdatedAt(new \DateTime('2024-12-01'));

        // Images pour le projet 2 - Portfolio
        $image2_1 = new ProjectImage();
        $image2_1->setFilename('12bf479c-ee53-4f9f-afce-00a1d63e93a6.png');
        $image2_1->setAlt('Page d\'accueil du portfolio');
        $image2_1->setIsPrimary(true);
        $image2_1->setSortOrder(1);
        $image2_1->setProject($project2);

        $image2_2 = new ProjectImage();
        $image2_2->setFilename('92abbe66-0172-43db-b461-ffdc66de5d3b.png');
        $image2_2->setAlt('Interface d\'administration');
        $image2_2->setIsPrimary(false);
        $image2_2->setSortOrder(2);
        $image2_2->setProject($project2);

        $image2_3 = new ProjectImage();
        $image2_3->setFilename('78f43f6b-842d-45af-8c36-04e4ba84abe2.png');
        $image2_3->setAlt('Page de projets');
        $image2_3->setIsPrimary(false);
        $image2_3->setSortOrder(3);
        $image2_3->setProject($project2);

        $manager->persist($project2);
        $manager->persist($image2_1);
        $manager->persist($image2_2);
        $manager->persist($image2_3);

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
        $project3->setCreatedAt(new \DateTime('2023-01-15'));
        $project3->setUpdatedAt(new \DateTime('2023-01-15'));

        // Images pour le projet 3 - Miroir connecté
        $image3_1 = new ProjectImage();
        $image3_1->setFilename('08ef6a47-ef0d-4379-ad7f-e283ef09272a.png');
        $image3_1->setAlt('Interface du miroir connecté');
        $image3_1->setIsPrimary(true);
        $image3_1->setSortOrder(1);
        $image3_1->setProject($project3);

        $image3_2 = new ProjectImage();
        $image3_2->setFilename('b78ec77d-c399-407e-8f74-540b7b276d62.png');
        $image3_2->setAlt('Configuration Raspberry Pi');
        $image3_2->setIsPrimary(false);
        $image3_2->setSortOrder(2);
        $image3_2->setProject($project3);

        $image3_3 = new ProjectImage();
        $image3_3->setFilename('3b26514c-3ec0-47c4-818c-5d2b8d23fe06.jpg');
        $image3_3->setAlt('Modules du miroir');
        $image3_3->setIsPrimary(false);
        $image3_3->setSortOrder(3);
        $image3_3->setProject($project3);

        $manager->persist($project3);
        $manager->persist($image3_1);
        $manager->persist($image3_2);
        $manager->persist($image3_3);

        $manager->flush();
    }
}
