<?php

namespace App\Handler\Project;

use App\Dto\Project\ProjectDTOInterface;
use App\Dto\Project\ProjectDTOResponse;
use App\Exception\NotFoundProjectException;
use App\Exception\PersistException;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteProjectByIdHandler
{
    public function __construct(
        readonly ProjectRepository $projectRepository,
        readonly EntityManagerInterface $em
    )
    { }

    public function handle(?ProjectDTOInterface $projectDTO): ?ProjectDTOResponse
    {
        $project = $this->projectRepository->find($projectDTO->projectId);

        if ($project === null) {
            throw new NotFoundProjectException();
        }

        try {
            $this->em->remove($project);
            $this->em->flush();
        } catch (\Throwable) {
            throw new PersistException();
        }

        return null;
    }
}
