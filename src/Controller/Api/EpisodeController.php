<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Chapter;
use App\Entity\Character;
use App\Entity\Episode;
use App\Entity\Place;
use App\Entity\Synopsis;
use App\Repository\ChapterRepository;
use App\Security\Voter\VoterAction;
use App\Service\ReorderService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/synopsis/{id}/episode')]
final class EpisodeController extends AbstractApiController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer,
        private ChapterRepository $chapterRepository
    ) {
    }

    #[Route('', name: 'api_synopsis_episode_create', methods: ['POST'])]
    public function createAction(Synopsis $synopsis, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);

        $chapterId = $request->query->getInt('chapter', 0);
        $chapter = null;
        if ($chapterId > 0) {
            $chapter = $this->chapterRepository->find($chapterId);
        }

        /** @var Episode */
        $episode = $this->serializer->deserialize($request->getContent(), Episode::class, 'json', ['groups' => ['index']]);

        $data = json_decode($request->getContent(), true);
        if (isset($data['places']) && count($data['places']) > 0) {
            $places = $this->entityManager->getRepository(Place::class)->findByIds($data['places'], $this->getUser());
            $episode->setPlaces(new ArrayCollection($places));
        }

        if (isset($data['characters']) && count($data['characters']) > 0) {
            $characters = $this->entityManager->getRepository(Character::class)->findByIds($data['characters'], $this->getUser());
            $episode->setCharacters(new ArrayCollection($characters));
        }

        $errors = $this->validate($episode);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        if (null !== $chapter) {
            $position = $chapter->getEpisodes()->count();
        } else {
            $position = $synopsis->getEpisodes()->count();
        }

        $episode->setChapter($chapter)->setSynopsis($synopsis)->setPosition($position)->setCreatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        if (null !== $chapter) {
            $this->entityManager->refresh($chapter);
        }

        return $this->json($synopsis, Response::HTTP_CREATED, [], ['groups' => ['index']]);
    }

    #[Route('/{episodeId}', name: 'api_synopsis_episode_edit', methods: ['PUT'])]
    public function editAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'episodeId')] Episode $episode, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $this->serializer->deserialize(
            $request->getContent(),
            Episode::class,
            'json',
            ['groups' => ['index'], AbstractNormalizer::OBJECT_TO_POPULATE => $episode,
        ]);

        $data = json_decode($request->getContent(), true);
        if (isset($data['places']) && count($data['places']) > 0) {
            $places = $this->entityManager->getRepository(Place::class)->findByIds($data['places'], $this->getUser());
            $episode->setPlaces(new ArrayCollection($places));
        }

        if (isset($data['characters']) && count($data['characters']) > 0) {
            $characters = $this->entityManager->getRepository(Character::class)->findByIds($data['characters'], $this->getUser());
            $episode->setCharacters(new ArrayCollection($characters));
        }

        $errors = $this->validate($episode);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $episode->setUpdatedAt(new \DateTime("now", new \DateTimeZone("Europe/Paris")));
        $this->entityManager->persist($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{episodeId}/valid', name: 'api_synopsis_episode_validate', methods: ['PUT'])]
    public function validateAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'episodeId')] Episode $episode): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::DELETE, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $episode->setValid(!$episode->isValid());
        $this->entityManager->persist($episode);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/{episodeId}', name: 'api_synopsis_episode_delete', methods: ['DELETE'])]
    public function deleteAction(#[MapEntity(id: 'episodeId')] Episode $episode): JsonResponse
    {
        return $this->deleteEntity($episode);
    }

    #[Route('/{episodeId}/position', name: 'api_synopsis_episode_position', methods: ['PUT'])]
    public function positionAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'episodeId')] Episode $episode, Request $request): JsonResponse
    {
        $oldChapter = $episode->getChapter();

        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $data = json_decode($request->getContent(), true);
        $newChapter = $this->getNewChapter($data, $synopsis);
        $position = (isset($data['position'])) ? $data['position'] : null;
        $reorderService = new ReorderService($this->entityManager);

        if (null !== $newChapter) {
            $position = (null === $position) ? $newChapter->getEpisodes()->count() : $position;
            $episode->setPosition($position);
            $newChapter->addEpisode($episode);
            $reorderService->setElements($newChapter->getEpisodes()->toArray())->insertToNewPosition($episode->getId(), $position);
        } else {
            $episodes = [];
            foreach ($synopsis->getEpisodes() as $element) {
                if (null === $episode->getChapter()) {
                    $episodes[] = $element;
                }
            }
            $episode->setChapter(null);
            $episodes[] = $episode;
            $position = (null === $position) ? count($episodes) : $position;
            $reorderService->setElements($episodes)->insertToNewPosition($episode->getId(), $position);
        }

        $this->entityManager->refresh($synopsis);
        if (null !== $oldChapter && $oldChapter !== $newChapter) {
            $reorderService->setElements($oldChapter->getEpisodes()->toArray())->sort()->redefineAllPosition();
        }

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{elementId}/archive', name: 'api_synopsis_episode_archive', methods: ['PUT'])]
    public function archiveAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'elementId')] Episode $episode): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        if ($episode->getSynopsis()->getId() !== $synopsis->getId()) {
            throw $this->createNotFoundException();
        }

        $episode->setArchived(!$episode->isArchived());
        $this->entityManager->persist($episode);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    private function getNewChapter(array $data, Synopsis $synopsis): ?Chapter
    {
        $target = (isset($data['target'])) ? $data['target'] : null;

        if (null === $target) {
            return null;
        }

        $newChapter = null;
        foreach ($synopsis->getChapters() as $chapter) {
            if ($chapter->getId() === (int) $target) {
                $newChapter = $chapter;
                break;
            }
        }

        return $newChapter;
    }
}
