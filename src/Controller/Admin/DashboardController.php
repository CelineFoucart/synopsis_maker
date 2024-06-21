<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Character;
use App\Entity\Episode;
use App\Entity\Log;
use App\Entity\Place;
use App\Entity\Synopsis;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'numberOfUsers' => $this->entityManager->getRepository(User::class)->count([]),
            'numberOfSynopsis' => $this->entityManager->getRepository(Synopsis::class)->count([]),
            'numberOfEpisodes' => $this->entityManager->getRepository(Episode::class)->count([]),
            'numberOfCharacters' => $this->entityManager->getRepository(Character::class)->count([]),
            'numberOfPlaces' => $this->entityManager->getRepository(Place::class)->count([]),
            'numberOfArticles' => $this->entityManager->getRepository(Article::class)->count([])
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration')
            ->setFaviconPath('assets/img/feather-bg-dark.png');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('assets/admin.css');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Log', 'fa fa-exclamation-triangle', Log::class);
        yield MenuItem::linkToUrl('Home', 'fas fa-globe', '/');
    }
}
