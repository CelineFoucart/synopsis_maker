<?php

namespace App\Controller\Api;

use App\Entity\Chapter;
use App\Entity\Synopsis;
use App\Security\Voter\SynopsisVoter;
use App\Service\ReorderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/synopsis/{id}')]
final class ChapterController extends AbstractApiController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/chapter', name: 'api_synopsis_chapter_create', methods: ['POST'])]
    public function createAction(Synopsis $synopsis, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::EDIT, $synopsis);

        /** @var Chapter */
        $chapter = $this->serializer->deserialize($request->getContent(), Chapter::class, 'json', ['groups' => ['index']]);
        $errors = $this->validate($chapter);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $position = $synopsis->getChapters()->count();

        $chapter->setSynopsis($synopsis)->setPosition($position);
        $this->entityManager->persist($chapter);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_CREATED, [], ['groups' => ['index']]);
    }

    #[Route('/chapter/{chapterId}', name: 'api_synopsis_chapter_edit', methods: ['PUT'])]
    public function editAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'chapterId')] Chapter $chapter, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::EDIT, $synopsis);
        if ($chapter->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $this->serializer->deserialize(
            $request->getContent(),
            Chapter::class,
            'json',
            ['groups' => ['index'], AbstractNormalizer::OBJECT_TO_POPULATE => $chapter,
        ]);

        $errors = $this->validate($chapter);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($chapter);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/chapter/{chapterId}/position', name: 'api_synopsis_chapter_position', methods: ['PUT'])]
    public function positionAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'chapterId')] Chapter $chapter, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::EDIT, $synopsis);
        if ($chapter->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['position'])) {
            return $this->json('Missing data', Response::HTTP_BAD_REQUEST);
        }

        $reorderService = new ReorderService($this->entityManager);
        $reorderService->setElements($synopsis->getChapters()->toArray())->insertToNewPosition($chapter->getId(), $data['position']);
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/chapter/{chapterId}', name: 'api_synopsis_chapter_delete', methods: ['DELETE'])]
    public function deleteAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'chapterId')] Chapter $chapter): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::DELETE, $synopsis);
        if ($chapter->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $this->entityManager->remove($chapter);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => ['index']]);
    }

    #[Route('/{elementId}/archive', name: 'api_synopsis_echapter_archive', methods: ['PUT'])]
    public function archiveAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'elementId')] Chapter $chapter): JsonResponse
    {
        $this->denyAccessUnlessGranted(SynopsisVoter::DELETE, $synopsis);
        if ($chapter->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $chapter->setArchived(!$chapter->isArchived());
        $this->entityManager->persist($chapter);

        foreach ($chapter->getEpisodes() as $episode) {
            $episode->setArchived($chapter->isArchived());
            $this->entityManager->persist($episode);
        }

        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }
}
