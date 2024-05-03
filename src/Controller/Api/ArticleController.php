<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Article;
use App\Security\Voter\VoterAction;
use App\Controller\Api\AbstractApiController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/article')]
final class ArticleController extends AbstractApiController
{
    #[Route('', name: 'api_article_index', methods: ['GET'])]
    public function indexAction(ArticleRepository $articleRepository): JsonResponse
    {
        return $this->json($articleRepository->findByAuthor($this->getUser()), Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_article_edit', methods: ['PUT'])]
    public function editAction(Article $article, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $article);
        $data = json_decode($request->getContent(), true);

        $article
            ->setTitle(isset($data['title']) ?  $data['title'] : null)
            ->setContent(isset($data['content']) ?  $data['content'] : null)
            ->setLink(isset($data['link']) ?  $data['link'] : null)
        ;

        if (isset($data['category'])) {
            foreach ($article->getAuthor()->getWorldBuildingCategories() as $category) {
                if ($category->getId() === (int) $data['category']) {
                    $article->setCategory($category);
                    break;
                }
            }
        }

        $errors = $this->validate($article);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->json($article, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_article_delete', methods: ['DELETE'])]
    public function deleteAction(Article $article): JsonResponse
    {
        return $this->deleteEntity($article);
    }
}
