<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Place;
use App\Entity\Synopsis;
use App\Security\Voter\VoterAction;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/synopsis')]
final class SynopsisPlaceController extends AbstractApiController
{
    #[Route('/{id}/places', name: 'api_synopsis_place', methods: ['POST'])]
    public function appendNewPlaceAction(Synopsis $synopsis, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);

        /** @var Place */
        $place = $serializer->deserialize($request->getContent(), Place::class, 'json', ['groups' => 'index']);

        $errors = $this->validate($place);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $place->addSynopsis($synopsis)->setAuthor($this->getUser());
        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($place);
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/places/{placeId}', name: 'api_synopsis_place_append', methods: ['PUT'])]
    public function appendPlaceAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'placeId')] Place $place): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        $synopsis->addPlace($place);
        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/places/{placeId}', name: 'api_synopsis_place_remove', methods: ['DELETE'])]
    public function unlinkPlaceAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'placeId')] Place $place): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        if (!$synopsis->getPlaces()->contains($place)) {
            throw $this->createNotFoundException();
        }

        $synopsis->removePlace($place);
        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }
}
