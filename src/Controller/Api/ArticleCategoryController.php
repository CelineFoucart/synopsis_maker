<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\WorldBuildingCategory;
use App\Security\Voter\VoterAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/article-category')]
final class ArticleCategoryController extends AbstractApiController
{
    #[Route('', name: 'api_article_category_index', methods: ['GET'])]
    public function indexAction(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        return $this->json($user->getWorldBuildingCategories(), Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('', name: 'api_article_category_create', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        /** @var WorldBuildingCategory */
        $category = $this->serializer->deserialize($request->getContent(), WorldBuildingCategory::class, 'json', ['groups' => 'index']);
        $category->setAuthor($this->getUser());

        return $this->persistEntity($category, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_article_category_edit', methods: ['PUT'])]
    public function editAction(WorldBuildingCategory $category, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $category, 'Access Denied.');

        /** @var Category */
        $category = $this->serializer->deserialize(
            $request->getContent(),
            WorldBuildingCategory::class, 'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $category, 'groups' => 'index']
        );

        return $this->persistEntity($category, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_article_category_delete', methods: ['DELETE'])]
    public function deleteAction(WorldBuildingCategory $category): JsonResponse
    {
        return $this->deleteEntity($category);
    }
}
