<?php

namespace App\Controller\Task;

use App\DataMapper\Task\TaskMapper;
use App\DTO\Task\CreateTaskDTO;
use App\Exception\ExceptionInterface;
use App\Handler\Task\CreateTaskHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreateTaskController extends AbstractController
{

    public function __construct(
        readonly NormalizerInterface $normalizer,
    )
    {}

    #[Route(
        path:'/projects/task',
        name: 'task_create',
        methods: ['POST']
    )]
    public function index(Request $request, CreateTaskHandler $handler, TaskMapper $taskMapper): JsonResponse
    {
        try {
            $taskDTO = $taskMapper->mapRequestToDTO($request->getContent(), CreateTaskDTO::class);
            $task = $handler->handle($taskDTO);

            return $this->json([
                'id' => $task->taskId,
                'success' => 'Task created successfully'
            ], JsonResponse::HTTP_CREATED);
        } catch (ExceptionInterface $exception) {

            return $this->json(
                ['error' => $exception->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (\Throwable) {
            return $this->json(
                ['error' => 'Internal Server Error'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
