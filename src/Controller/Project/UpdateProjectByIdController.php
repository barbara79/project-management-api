<?php

namespace App\Controller\Project;

use App\DataMapper\Project\ProjectMapper;
use App\Dto\Project\GetProjectDTO;
use App\Dto\Project\UpdateProjectDTO;
use App\Exception\ExceptionInterface;
use App\Handler\Project\UpdateProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProjectByIdController extends AbstractController
{
    #[Route(
        path: '/projects/{projectId}',
        name: 'project_update',
        requirements: ['projectId' => '\d+'],
        methods: ['PATCH'],
    )]
    public function index(Request $request, int $projectId, UpdateProjectHandler $handler, ProjectMapper $projectMapper)
    {
        try {
            $getProjectDTO = GetProjectDTO::from($projectId);
            $bodyDTO = $projectMapper->mapRequestToDTO($request->getContent(), UpdateProjectDTO::class);

            /** @var UpdateProjectDTO $bodyDTO */
            $project = $handler->handle($getProjectDTO, $bodyDTO);

            return $this->json([
                'id' => $project->projectId,
                'success' => 'Project updated successfully'
            ], JsonResponse::HTTP_OK);
        } catch (ExceptionInterface $exception) {
            return $this->json(
                ['error' => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (\Throwable ) {
            return $this->json(
                ['error' => 'Internal Server Error'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
