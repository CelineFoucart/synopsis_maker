<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\SynopsisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/synopsis')]
final class SynopsisController extends AbstractController
{
    
    #[Route('', name: 'api_synopsis_index', methods:['GET'])]
    public function index(Request $request, SynopsisRepository $synopsisRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->createAccessDeniedException();
        }

        $params = [
            'page' => $request->query->getInt('page', 1),
            'query' => $request->query->get('query', null),
            'limit' => $request->query->get('limit', 10)
        ];

        $pagination = $synopsisRepository->findPaginated($user, $params);
        $meta = $pagination->getPaginationData();
        $meta['limit'] = $params['limit'];

        return $this->json(['synopses' => $pagination->getItems(), 'meta' => $meta], Response::HTTP_OK, [], ['groups' => ['index']]);

    }
}
