<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', methods: ['GET'])]
    public function sitemap(): Response
    {
        $urls = [
            [
                'loc' => $this->generateUrl('app_home', ['_locale' => 'fr'], true),
                'lastmod' => '2025-01-08',
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ],
            [
                'loc' => $this->generateUrl('app_home', ['_locale' => 'en'], true),
                'lastmod' => '2025-01-08',
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ],
            [
                'loc' => $this->generateUrl('app_projects', ['_locale' => 'fr'], true),
                'lastmod' => '2025-01-08',
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'loc' => $this->generateUrl('app_projects', ['_locale' => 'en'], true),
                'lastmod' => '2025-01-08',
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ]
        ];

        $response = $this->render('sitemap/sitemap.xml.twig', [
            'urls' => $urls
        ]);

        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }
}
