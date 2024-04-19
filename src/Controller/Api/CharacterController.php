<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Controller\Api\AbstractApiController;
use App\Security\Voter\CharacterVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/character')]
final class CharacterController extends AbstractApiController
{
    #[Route('', name: 'api_character_index', methods: ['GET'])]
    public function indexAction(CharacterRepository $characterRepository): JsonResponse
    {
        return $this->json($characterRepository->findByAuthor($this->getUser()), Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_character_edit', methods: ['PUT'])]
    public function editAction(Character $character, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(CharacterVoter::EDIT, $character);

        /** @var Character */
        $character = $serializer->deserialize(
            $request->getContent(),
            Character::class, 'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $character, 'groups' => 'index']
        );

        $errors = $this->validate($character);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($character);
        $this->entityManager->flush();

        return $this->json($character, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_character_delete', methods: ['DELETE'])]
    public function deleteAction(Character $character): JsonResponse
    {
        $this->denyAccessUnlessGranted(CharacterVoter::DELETE, $character);
        $this->entityManager->remove($character);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT, [], ['groups' => ['index']]);
    }
}
