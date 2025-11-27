<?php

namespace App\Handler;

use App\Dto\GetProjectDTO;
use App\Dto\ProjectDTOInterface;
use App\Dto\ProjectDTOResponse;
use App\Entity\Project;
use App\Exception\NotFoundProjectException;
use App\Repository\ProjectRepository;

class GetProjectByIdHandler
{
    public function __construct(
        readonly ProjectRepository $projectRepository)
    {}

    public function handle(?ProjectDTOInterface $projectDTO):  ProjectDTOResponse
    {
        $project = $this->projectRepository->find($projectDTO->projectId);

        if ($project === null) {
            throw new NotFoundProjectException();
        }

        return new ProjectDTOResponse(
            projectId: $project->getId(),
            title: $project->getTitle(),
            deadline: $project->getDeadline()?->format('Y-m-d') ?? '',
            owner: $project->getOwner()
        );
    }
}
