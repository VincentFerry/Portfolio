<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController
{
    public function show(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null): Response
    {
        $statusCode = $exception->getStatusCode();
        $statusText = $exception->getStatusText();

        // Templates spécifiques selon le code d'erreur
        if ($statusCode === 404) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'status_code' => $statusCode,
                'status_text' => $statusText,
                'exception' => $exception,
            ], new Response('', $statusCode));
        }

        if ($statusCode === 500) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'status_code' => $statusCode,
                'status_text' => $statusText,
                'exception' => $exception,
            ], new Response('', $statusCode));
        }

        // Template générique pour les autres erreurs
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => $statusCode,
            'status_text' => $statusText,
            'exception' => $exception,
        ], new Response('', $statusCode));
    }
}
