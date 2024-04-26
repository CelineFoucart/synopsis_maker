<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Synopsis;
use App\Security\Voter\VoterAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/synopsis')]
final class SynopsisTaskController extends AbstractApiController
{
    #[Route('/{id}/tasks', name: 'api_synopsis_task', methods: ['PUT'])]
    public function editTasksAction(Synopsis $synopsis, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tasks = [];
        foreach ($data as $row) {
            $category = isset($row['category']) ? (int) $row['category'] : 0;
            if ($category < 0 || $category > 2) {
                $category = 0;
            }

            $tasks[] = [
                'id' => isset($row['id']) && null !== $row['id'] ? $row['id'] : uniqid(),
                'title' => isset($row['title']) ? $row['title'] : 'Tâche',
                'content' => isset($row['content']) ? $row['content'] : '',
                'position' => isset($row['position']) ? $row['position'] : count($tasks),
                'category' => $category,
            ];
        }

        $synopsis->setTasks($tasks);

        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    #[Route('/{id}/tasks/{task}/{category}/{position}', name: 'api_synopsis_task_reorder', methods: ['PUT'])]
    public function reorderTasksAction(Synopsis $synopsis, string $task, int $category, int $position): JsonResponse
    {
        $this->denyAccessUnlessGranted(VoterAction::EDIT, $synopsis);

        $toReorderTasks = [];
        $notReorderedTasks = [];

        foreach ($synopsis->getTasks() as $element) {
            if ($element['id'] === $task) {
                $element['category'] = $category;
            }

            if ($element['category'] === $category) {
                $toReorderTasks[] = $element;
            } else {
                $notReorderedTasks[] = $element;
            }
        }

        $toReorderTasks = $this->reorderTaskArray($toReorderTasks, $task, $position);
        $tasks = [...$notReorderedTasks, ...$toReorderTasks];
        $synopsis->setTasks($tasks);
        $this->entityManager->persist($synopsis);
        $this->entityManager->flush();

        return $this->json($synopsis, Response::HTTP_OK, [], ['groups' => ['index']]);
    }

    private function reorderTaskArray(array $toReorderTasks, string $task, int $position): array
    {
        if (!empty($toReorderTasks)) {
            usort($toReorderTasks, fn (array $a, array $b) => $a['position'] <=> $b['position']);
        }

        for ($i = 0; $i < count($toReorderTasks); ++$i) {
            if ($toReorderTasks[$i]['id'] === $task) {
                $part2 = array_splice($toReorderTasks, $i, 1);
                $part1 = array_slice($toReorderTasks, 0, $position);
                $part3 = array_slice($toReorderTasks, $position);
                $toReorderTasks = array_merge($part1, $part2, $part3);
            }
        }

        for ($i = 0; $i < count($toReorderTasks); ++$i) {
            $toReorderTasks[$i]['position'] = $i;
        }

        return $toReorderTasks;
    }
}
