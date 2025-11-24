<?php

namespace App\Controller;

use App\DataMapper\ProjectMapper;
use App\Dto\CreateProjectDTO;
use App\Dto\GetProjectDTO;
use App\Exception\ExceptionInterface;
use App\Handler\UpdateProjectHandler;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            $bodyDTO = $projectMapper->mapRequestToDTO($request->getContent(), CreateProjectDTO::class);

            $handler->handle($getProjectDTO, $bodyDTO);

            return $this->json(['success' => 'Project created successfully'], JsonResponse::HTTP_OK);
        } catch (ExceptionInterface $exception) {
            return $this->json(
                ['error' => $exception->getMessage()],
                $exception->getCode()
            );
        }
    }
}
