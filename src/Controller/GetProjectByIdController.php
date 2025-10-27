<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetProjectByIdController extends AbstractController
{
    #[Route(
        path: '/projects/{projectId}',
        name: 'project_get_by_id',
        requirements: ['projectId' => '\d+'],
        methods: ['GET'],
    )]
    public function getProjectById(int $projectId, ProjectRepository $projectRepository): JsonResponse
    {
        error_log('>>> GetProjectByIdController triggered');
        $project = $projectRepository->find($projectId);

        if (!$project) {
            return $this->json(['error' => 'not found'], 404);
        }

        return $this->json([
            'id' => $project->getId(),
            'title' => $project->getTitle(),
            'description' => $project->getDescription(),
            'deadline' => $project->getDeadline()?->format('Y-m-d'),
            'owner' => $project->getOwner(),
        ]);
    }
}
