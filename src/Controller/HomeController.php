<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/terms', name: 'app_terms')]
    public function termsAction(): Response
    {
        return $this->render('home/terms.html.twig');
    }

    #[Route('/privacy', name: 'app_privacy')]
    public function privacyAction(): Response
    {
        return $this->render('home/privacy.html.twig');
    }
}
