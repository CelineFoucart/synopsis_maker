<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\AbstractApiController;
use App\Repository\SynopsisRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/explore')]
final class ExploreController extends AbstractApiController
{
    #[Route('', name: 'api_explore_index', methods:['GET'])]
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
}
