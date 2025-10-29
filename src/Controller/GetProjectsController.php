<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class GetProjectsController extends AbstractController
{
    #[Route(
        path: '/projects',
        name: 'project_get_all',
        methods: ['GET'],
        requirements: ['_format' => 'json']
    )]
    public function getProjects(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findAll();

        $data = [];

        foreach ($projects as $project) {
            $data[] = [
                'id' => $project->getId(),
                'title' => $project->getTitle(),
                'description' => $project->getDescription(),
                'deadline' => $project->getDeadline()?->format('Y-m-d'),
                'owner' => $project->getOwner(),
            ];
        }

        return $this->json($data);
    }
}
