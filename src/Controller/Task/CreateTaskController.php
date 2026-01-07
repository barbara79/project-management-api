<?php

namespace App\Controller\Project;

use App\DataMapper\Project\ProjectMapper;
use App\Dto\Project\CreateProjectDTO;
use App\Exception\ExceptionInterface;
use App\Handler\Project\CreateProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreateProjectController extends AbstractController
{

    public function __construct(
        readonly NormalizerInterface $normalizer,
    )
    {}

    #[Route(
        path:'/projects',
        name: 'project_create',
        methods: ['POST']
    )]
    public function index(Request $request, CreateProjectHandler $handler, ProjectMapper $projectMapper): JsonResponse
    {
        try {
            $projectDTO = $projectMapper->mapRequestToDTO($request->getContent(), CreateProjectDTO::class);
            $project = $handler->handle($projectDTO);

            return $this->json([
                'id' => $project->projectId,
                'success' => 'Project created successfully'
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
