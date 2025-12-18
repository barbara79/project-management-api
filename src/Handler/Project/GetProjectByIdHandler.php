<?php

namespace App\Handler\Project;

use App\Dto\Project\ProjectDTOInterface;
use App\Dto\Project\ProjectDTOResponse;
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
            description: $project->getDescription(),
            deadline: $project->getDeadline()?->format('Y-m-d') ?? '',
            owner: $project->getOwner()
        );
    }
}
