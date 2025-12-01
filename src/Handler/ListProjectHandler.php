<?php

namespace App\Handler;


use App\Dto\ProjectDTOResponse;
use App\Exception\NotFoundProjectException;
use App\Repository\ProjectRepository;

class ListProjectHandler
{

    public function __construct(
        readonly ProjectRepository $projectRepository)
    {}

    public function handle(): ?array
    {
        $projects = $this->projectRepository->findAll();

        if (count($projects) === 0) {
            throw new NotFoundProjectException();
        }

        $projectsDTO = [];
        foreach ($projects as $project) {
            $projectsDTO[] = new ProjectDTOResponse(
                $project->getId(),
                $project->getTitle(),
                $project->getDescription(),
                $project->getDeadline()->format('Y-m-d'),
                $project->getOwner()
            );
        }

        return $projectsDTO;
    }
}
