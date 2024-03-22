<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Chapter;
use App\Entity\Synopsis;
use App\Security\Voter\SynopsisVoter;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Api\AbstractApiController;
use App\Entity\Episode;
use App\Repository\ChapterRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/synopsis/{id}/episode')]
class EpisodeController extends AbstractApiController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private ChapterRepository $chapterRepository
    ) {
    }

    #[Route('', name: 'api_synopsis_episode_create', methods:['POST'])]
    public function createAction(Synopsis $synopsis, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::EDIT, $synopsis);

        $chapterId = $request->query->getInt('chapter', 0);
        $chapter = null;
        if ($chapterId > 0) {
            $chapter = $this->chapterRepository->find($chapterId);
        }

        /** @var Episode */
        $episode = $this->serializer->deserialize($request->getContent(), Episode::class, 'json', ['groups' => ['index']]);
        $errors = $this->validate($episode);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        if ($chapter !== null) {
            $position = $chapter->getEpisodes()->count();
        } else {
            $position = $synopsis->getEpisodes()->count();
        }
        

        $episode->setChapter($chapter)->setSynopsis($synopsis)->setPosition($position)->setCreatedAt(new DateTimeImmutable());
        $this->entityManager->persist($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        if ($chapter !== null) {
            $this->entityManager->refresh($chapter);
        }

        return $this->json($synopsis, Response::HTTP_CREATED, [], ['groups' => ['index']]);
    }

    #[Route('/{episodeId}', name: 'api_synopsis_episode_edit', methods:['PUT'])]
    public function editAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'episodeId')] Episode $episode, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::EDIT, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $this->serializer->deserialize(
            $request->getContent(), 
            Episode::class, 
            'json', 
            ['groups' => ['index'], AbstractNormalizer::OBJECT_TO_POPULATE => $episode
        ]);

        $errors = $this->validate($episode);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $episode->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{episodeId}/valid', name: 'api_synopsis_episode_validate', methods:['PUT'])]
    public function validateAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'episodeId')] Episode $episode): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::DELETE, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $episode->setValid(!$episode->isValid());
        $this->entityManager->persist($episode);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/{episodeId}', name: 'api_synopsis_episode_delete', methods:['DELETE'])]
    public function deleteAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'episodeId')] Episode $episode): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::DELETE, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $this->entityManager->remove($episode);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
