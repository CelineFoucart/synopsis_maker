<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Security\Voter\CategoryVoter;
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
    public function createAction(SerializerInterface $serializer, Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->createAccessDeniedException();
        }

        /** @var Category */
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json', ['groups' => 'index']);
        $category->setAuthor($this->getUser());

        $errors = $this->validate($category);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json($category, Response::HTTP_CREATED, [], ['groups' => 'index']);
    }

    #[Route('/{id}', name: 'api_category_edit', methods: ['PUT'])]
    public function editAction(Category $category, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $this->denyAccessUnlessGranted(CategoryVoter::EDIT, $category, 'Access Denied.');

        /** @var Category */
        $category = $serializer->deserialize(
            $request->getContent(),
            Category::class, 'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $category, 'groups' => 'index']
        );

        $errors = $this->validate($category);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json($category, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function deleteAction(Category $category): JsonResponse
    {
        $this->denyAccessUnlessGranted(CategoryVoter::DELETE, $category, 'Access Denied.');
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => ['index']]);
    }
}
