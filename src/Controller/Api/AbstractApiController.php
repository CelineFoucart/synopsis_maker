<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Place;
use App\Entity\Chapter;
use App\Entity\Episode;
use App\Entity\Category;
use App\Entity\Synopsis;
use App\Entity\Character;
use App\Security\Voter\VoterAction;
use App\Entity\WorldBuildingCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer
    ) {
    }

    /**
     * Persist an entity.
     * 
     * @param mixed $entity
     * @param int $respondeCode
     * 
     * @return JsonResponse
     */
    protected function persistEntity(mixed $entity, int $respondeCode): JsonResponse
    {
        $errors = $this->validate($entity);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->json($entity, $respondeCode, [], ['groups' => 'index']);
    }

    /**
     * Validate an entity.
     */
    protected function validate(mixed $entity): array
    {
        $errors = [];
        $violations = $this->validator->validate($entity);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        return $errors;
    }

    /**
     * Delete the entity if the current user has permissions.
     * 
     * @param mixed $entity
     * 
     * @return JsonResponse
     */
    protected function deleteEntity(mixed $entity): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::DELETE, $entity, 'Access Denied.');
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
