<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UpdateProjectByIdController extends AbstractController
{
    #[Route(
        path: '/projects/{projectId}',
        name: 'project_update',
        methods: ['PATCH'],
    )]
    public function updateProject(Request $request, int $projectId, ProjectRepository $projectRepository, EntityManagerInterface $em)
    {
        $body = $request->getContent();

        if (empty($body)) {
            return $this->json([ 'error' => 'No data provided' ], 400);
        }

        $data = json_decode($body, true);

        $project = $projectRepository->find($projectId);
        if (empty($project)) {
            return $this->json([ 'error' => 'Project not found' ], 404);
        }

        if (isset($data['name'])) {
            $project->setName($data['name']);
        }

        if (isset($data['description'])) {
            $project->setDescription($data['description']);
        }

        if (isset($data['deadline'])) {
            $project->setDeadline(new \DateTime($data['deadline']));
        }

        if (isset($data['owner'])) {
            $project->setOwner($data['owner']);
        }

        $em->flush();

        return $this->json([
            'project' => $project,
            'message' => 'Project updated successfully'
        ], 200);
    }
}
