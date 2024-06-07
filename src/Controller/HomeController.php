<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->getUser() instanceof User) {
            return $this->render('home/synopsis.html.twig');
        }

        return $this->render('home/index_anonymous.html.twig');
    }

    #[Route('/explore', name: 'app_explore')]
    public function exploreAction(): Response
    {
        return $this->render('home/explore.html.twig');
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
