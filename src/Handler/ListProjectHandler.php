<?php

namespace App\Handler;

use App\Dto\ListProjectDTO;
use App\Dto\ProjectDTOInterface;
use App\Dto\ProjectDTOResponse;
use App\Exception\NotFoundProjectException;
use App\Handler\ProjectHandlerInterface;
use App\Repository\ProjectRepository;

class ListProjectHandler
{

    public function __construct(
        readonly ProjectRepository $projectRepository)
    {}

    public function handle(): ?array
    {
        $projects = $this->projectRepository->findAll();

        $projectsDTO = [];
        foreach ($projects as $project) {
            $projectsDTO[] = new ProjectDTOResponse(
                $project->getId(),
                $project->getTitle(),
                $project->getDeadline()->format('Y-m-d'),
                $project->getOwner()
            );
        }

        return $projectsDTO;
    }
}
