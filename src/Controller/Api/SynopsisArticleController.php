<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Article;
use App\Entity\Synopsis;
use App\Entity\User;
use App\Security\Voter\VoterAction;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/synopsis')]
final class SynopsisArticleController extends AbstractApiController
{
    #[Route('/{id}/articles', name: 'api_synopsis_article', methods: ['POST'])]
    public function appendNewArticleAction(Synopsis $synopsis, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();
        assert($user instanceof User);

        $article = (new Article())
            ->setTitle(isset($data['title']) ?  $data['title'] : null)
            ->setContent(isset($data['content']) ?  $data['content'] : null)
            ->setLink(isset($data['link']) ?  $data['link'] : null)
            ->setAuthor($user)
            ->addSynopsis($synopsis)
        ;
            
        if (isset($data['category'])) {
            foreach ($user->getWorldBuildingCategories() as $category) {
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
        
        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($article);
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/articles/{articleId}', name: 'api_synopsis_article_append', methods: ['PUT'])]
    public function appendArticleAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'articleId')] Article $article): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        $synopsis->addArticle($article);
        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/articles/{articleId}', name: 'api_synopsis_article_remove', methods: ['DELETE'])]
    public function unlinkAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'articleId')] Article $article): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        if (!$synopsis->getArticles()->contains($article)) {
            throw $this->createNotFoundException();
        }

        $synopsis->removeArticle($article);
        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }
}
