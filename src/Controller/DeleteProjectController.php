<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProjectController extends AbstractController
{
    #[Route('/projects/{projectId}', name: 'project_delete', methods: ['DELETE'])]
   public function deleteProject(int $projectId, ProjectRepository $projectRepository, EntityManagerInterface $em)
    {
        $project = $projectRepository->find($projectId);

        if (!$project) {
            return $this->json(['error' => 'project not found'], 404);
        }

        try {
            $em->remove($project);
            $em->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

        return $this->json(['success' => 'project deleted'], 200);
    }
}
