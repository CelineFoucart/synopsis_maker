<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Synopsis;
use App\Repository\SynopsisRepository;
use App\Controller\Api\AbstractApiController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api')]
final class ExploreController extends AbstractApiController
{
    #[Route('/explore', name: 'api_explore_index', methods:['GET'])]
    public function indexAction(SynopsisRepository $synopsisRepository, Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $field = $request->query->get('field', 's.title');
        $order = $request->query->get('order', 'asc');
        $query = $request->query->get('query', null);
        $pagination = $synopsisRepository->findPublicPaginated($page, $limit, $field, $order, $query);
        $meta = $pagination->getPaginationData();

        return $this->json(['synopses' => $pagination->getItems(), 'meta' => $meta], Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    #[Route('/explore/{id}', name: 'api_explore_synopsis', methods:['GET'])]
    public function showAction(Synopsis $synopsis): JsonResponse
    {
        if ($synopsis->isPublic() === false) {
            throw $this->createNotFoundException();
        }

        $settings = $synopsis->getSettings();
        $publicData = [
            'id' => $synopsis->getId(),
            'title' => $synopsis->getTitle(),
            'categories' => $synopsis->getCategories(),
            'author' => $synopsis->getAuthor(),
            'pitch' => $synopsis->getPitch(),
            'description' => $synopsis->getDescription(),
            'chapters' => $settings['showContent'] ? $synopsis->getChapters() : [],
            'episodes' => $settings['showContent'] ? $synopsis->getEpisodes() : [],
            'characters' => $settings['showCharacters'] ? $synopsis->getCharacters() : [],
            'places' => $settings['showPlaces'] ? $synopsis->getPlaces() : [],
            'settings' => [
                'showContent' => $settings['showContent'],
                'showCharacters' => $settings['showCharacters'],
                'showPlaces' => $settings['showPlaces'],
            ]
        ];

        return $this->json($publicData, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/user', name: 'api_explore_user_index', methods:['GET'])]
    public function sindexUserAction(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/user/{id}', name: 'api_explore_user_show', methods:['GET'])]
    public function showUserAction(User $user, SynopsisRepository $synopsisRepository): JsonResponse
    {
        $publicData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'createdAt' => $user->getCreatedAt(),
            'profile' => $user->getProfile(),
            'synopses' => $synopsisRepository->findUserPublicList($user),
        ];

        return $this->json($publicData, Response::HTTP_OK, [], ['groups' => ['show-author']]);
    }


}
