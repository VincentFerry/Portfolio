<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DQNController extends AbstractController
{
    #[Route('/dqn', name: 'app_dqn')]
    public function index(): Response
    {
        return $this->render('dqn/index.html.twig');
    }
}
