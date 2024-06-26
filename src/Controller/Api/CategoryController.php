<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Security\Voter\VoterAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/category')]
final class CategoryController extends AbstractApiController
{
    #[Route('', name: 'api_category_index', methods: ['GET'])]
    public function indexAction(CategoryRepository $categoryRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->createAccessDeniedException();
        }

        return $this->json($categoryRepository->findByAuthor($user), Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('', name: 'api_category_create', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        /** @var Category */
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json', ['groups' => 'index']);
        $category->setAuthor($this->getUser());

        return $this->persistEntity($category, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_category_edit', methods: ['PUT'])]
    public function editAction(Category $category, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $category, 'Access Denied.');

        /** @var Category */
        $category = $this->serializer->deserialize(
            $request->getContent(),
            Category::class, 'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $category, 'groups' => 'index']
        );

        return $this->persistEntity($category, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function deleteAction(Category $category): JsonResponse
    {
        return $this->deleteEntity($category);
    }
}
