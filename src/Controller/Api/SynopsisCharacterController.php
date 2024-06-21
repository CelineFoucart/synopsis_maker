<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Synopsis;
use App\Entity\Character;
use App\Security\Voter\VoterAction;
use App\Controller\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/synopsis')]
final class SynopsisCharacterController extends AbstractApiController
{
    #[Route('/{id}/characters', name: 'api_synopsis_character', methods: ['POST'])]
    public function appendNewPlaceAction(Synopsis $synopsis, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);

        /** @var Place */
        $character = $serializer->deserialize($request->getContent(), Character::class, 'json', ['groups' => 'index']);

        $errors = $this->validate($character);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $character->addSynopsis($synopsis)->setAuthor($this->getUser());
        $synopsis->setUpdatedAt(new \DateTime("now", new \DateTimeZone("Europe/Paris")));
        $this->entityManager->persist($character);
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();
        $this->entityManager->refresh($synopsis);

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/characters/{characterId}', name: 'api_synopsis_character_append', methods: ['PUT'])]
    public function appendAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'characterId')] Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        $synopsis->addCharacter($character);
        $synopsis->setUpdatedAt(new \DateTime("now", new \DateTimeZone("Europe/Paris")));
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/characters/{characterId}', name: 'api_synopsis_character_remove', methods: ['DELETE'])]
    public function unlinkAction(#[MapEntity(id: 'id')] Synopsis $synopsis, #[MapEntity(id: 'characterId')] Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);
        if (!$synopsis->getCharacters()->contains($character)) {
            throw $this->createNotFoundException();
        }

        $synopsis->removeCharacter($character);
        $synopsis->setUpdatedAt(new \DateTime("now", new \DateTimeZone("Europe/Paris")));
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }
}
