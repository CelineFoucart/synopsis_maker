<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Synopsis;
use App\Service\SynopsisHandler;
use App\Repository\SynopsisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/synopsis')]
final class SynopsisController extends AbstractApiController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        private SluggerInterface $slugger, 
        protected ValidatorInterface $validator
    ) {
    }

    #[Route('', name: 'api_synopsis_index', methods:['GET'])]
    public function index(Request $request, SynopsisRepository $synopsisRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->createAccessDeniedException();
        }

        $params = [
            'page' => $request->query->getInt('page', 1),
            'query' => $request->query->get('query', null),
            'limit' => $request->query->get('limit', 10),
            'categories' => [],
        ];

        $categoriesString = $request->query->get('categories', '-');
        if ($categoriesString !== '-') {
            $params['categories'] = explode('-', $categoriesString);
        }

        $pagination = $synopsisRepository->findPaginated($user, $params);
        $meta = $pagination->getPaginationData();

        return $this->json(['synopses' => $pagination->getItems(), 'meta' => $meta], Response::HTTP_OK, [], ['groups' => ['index']]);

    }

    #[Route('', name: 'api_synopsis_create', methods:["POST"])]
    public function createAction(Request $request, SynopsisHandler $handler): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->createAccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);
        $errors = $handler->setSynopsis(new Synopsis())->edit($data, $user)->getErrors();
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $synopsis = $handler->getSynopsis();
        $errors = $this->validate($synopsis);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $synopsis->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_CREATED, [], ['groups' => 'index']);
    }

    #[Route('/{id}', name: 'api_synopsis_show', methods:["GET"])]
    public function showAction(#[MapEntity(expr: 'repository.findOneById(id)')] Synopsis $synopsis): JsonResponse
    {
        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}', name: 'api_synopsis_edit', methods:["PUT"])]
    public function editAction(
        #[MapEntity(expr: 'repository.findOneById(id)')] Synopsis $synopsis, 
        #[CurrentUser()] User $user, 
        Request $request,
        SynopsisHandler $handler
    ): JsonResponse {
        if ($user->getId() !== $synopsis->getAuthor()->getId()) {
            $this->createAccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);
        $errors = $handler->setSynopsis($synopsis)->edit($data, $user)->getErrors();
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $synopsis = $handler->getSynopsis();
        $errors = $this->validate($synopsis);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $synopsis->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/legend', name: 'api_synopsis_legend_edit', methods:["PUT"])]
    public function legentEditAction(Synopsis $synopsis, #[CurrentUser()] User $user, Request $request): JsonResponse
    {
        if ($user->getId() !== $synopsis->getAuthor()->getId()) {
            $this->createAccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['legend'])) {
            return $this->json('Le formulaire est incomplet', Response::HTTP_BAD_REQUEST);
        }

        $synopsis->setLegend($data['legend']);
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'api_synopsis_delete', methods:["DELETE"])]
    public function deleteAction(Synopsis $synopsis, #[CurrentUser()] User $user): JsonResponse
    {
        if ($user->getId() !== $synopsis->getAuthor()->getId()) {
            $this->createAccessDeniedException();
        }

        $this->entityManager->remove($synopsis);
        $this->entityManager->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
