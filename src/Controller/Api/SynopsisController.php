<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Synopsis;
use App\Repository\CategoryRepository;
use App\Repository\SynopsisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'limit' => $request->query->get('limit', 10)
        ];

        $pagination = $synopsisRepository->findPaginated($user, $params);
        $meta = $pagination->getPaginationData();
        $meta['limit'] = $params['limit'];

        return $this->json(['synopses' => $pagination->getItems(), 'meta' => $meta], Response::HTTP_OK, [], ['groups' => ['index']]);

    }

    #[Route('', name: 'api_synopsis_create', methods:["POST"])]
    public function createAction(Request $request, CategoryRepository $categoryRepository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->createAccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $synopsis = (new Synopsis())
            ->setTitle(isset($data['title']) ? $data['title'] : null)
            ->setPitch(isset($data['pitch']) ? $data['pitch'] : null)
            ->setAuthor($this->getUser());
        
        if (!isset($data['categories'])) {
            return $this->json(['categories'=>'Ce champ est obligatoire'], Response::HTTP_BAD_REQUEST);
        }

        $categories = $categoryRepository->findByIds($data['categories'], $this->getUser());
        if (empty($categories)) {
            return $this->json(['categories'=>'Ce champ est obligatoire'], Response::HTTP_BAD_REQUEST);
        }

        foreach ($categories as $category) {
            $synopsis->addCategory($category);
        }

        $errors = $this->validate($synopsis);
        if (!empty($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $synopsis->setSlug((string) $this->slugger->slug($synopsis->getTitle()))->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_CREATED, [], ['groups' => 'index']);
    }
}
