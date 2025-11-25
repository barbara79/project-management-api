<?php

namespace App\Handler;

use App\Dto\GetProjectDTO;
use App\Dto\ProjectDTOResponse;
use App\Dto\UpdateProjectDTO;
use App\Exception\PersistException;
use App\Repository\ProjectRepository;
use App\Exception\NotFoundProjectException;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProjectHandler
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private EntityManagerInterface $em,
        private NotificationService $notificationService,
    )
    {}

    public function handle(GetProjectDTO $projectDTO, UpdateProjectDTO $updateProjectDTO): ?ProjectDTOResponse
    {
        $project = $this->projectRepository->find($projectDTO->projectId);

        if ($project === null) {
            throw new NotFoundProjectException();
        }

        if (isset($updateProjectDTO->title)) {
            $project->setTitle($updateProjectDTO->title);
        }

        if (isset($updateProjectDTO->description)) {
            $project->setDescription($updateProjectDTO->description);
        }

        if (isset($updateProjectDTO->deadline)) {
            $project->setDeadline(new \DateTimeImmutable($updateProjectDTO->deadline));
        }

        if (isset($updateProjectDTO->owner)) {
            $project->setOwner($updateProjectDTO->owner);
        }

        try {
            $this->em->persist($project);
            $this->em->flush();
        } catch (\Throwable ) {
            throw new PersistException();
        }

        if (isset($updateProjectDTO->owner)) {
            $this->notificationService->notify($project);
        }

        return null;
    }
}
