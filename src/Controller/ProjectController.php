<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_synopsis')]
    public function index(): Response
    {
        return $this->render('project/synopsis.html.twig');
    }

    #[Route('/gallery', name: 'app_gallery')]
    public function gallery(): Response
    {
        return $this->render('project/gallery.html.twig');
    }

    
}
