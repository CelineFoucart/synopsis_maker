<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Place;
use App\Security\Voter\PlaceVoter;
use App\Repository\PlaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/place')]
final class PlaceController extends AbstractApiController
{
    #[Route('', name: 'api_place_index', methods: ['GET'])]
    public function indexAction(PlaceRepository $placeRepository): JsonResponse
    {
        return $this->json($placeRepository->findByAuthor($this->getUser()), Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_place_edit', methods: ['PUT'])]
    public function editAction(Place $place, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaceVoter::EDIT, $place);

        /** @var Place */
        $place = $serializer->deserialize(
            $request->getContent(),
            Place::class, 'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $place, 'groups' => 'index']
        );

        $errors = $this->validate($place);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($place);
        $this->entityManager->flush();

        return $this->json($place, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_place_delete', methods: ['DELETE'])]
    public function deleteAction(Place $place): JsonResponse
    {
        $this->denyAccessUnlessGranted(PlaceVoter::DELETE, $place);
        $this->entityManager->remove($place);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => ['index']]);
    }
}
