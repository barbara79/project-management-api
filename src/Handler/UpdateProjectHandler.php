<?php

namespace App\Handler;

use App\Dto\ProjectDTOInterface;
use App\Dto\ProjectDTOResponse;
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

    public function handle(?ProjectDTOInterface $projectDTO, ProjectDTOInterface $updatedDTO): ?ProjectDTOResponse
    {
        $project = $this->projectRepository->find($projectDTO->projectId);

        if ($project === null) {
            throw new NotFoundProjectException();
        }

        if (isset($updatedDTO->title)) {
            $project->setTitle($updatedDTO->title);
        }

        if (isset($updatedDTO->description)) {
            $project->setDescription($updatedDTO->description);
        }

        if (isset($updatedDTO->deadline)) {
            $project->setDeadline(new \DateTimeImmutable( $updatedDTO->deadline));
        }

        if (isset($updatedDTO->owner)) {
            $project->setOwner($updatedDTO->owner);
        }

        try {
            $this->em->persist($project);
            $this->em->flush();
        } catch (\Throwable $e) {
            throw new PersistException();
        }

        if (isset($updatedDTO->owner)) {
            $this->notificationService->notify($project);
        }

        return null;
    }
}
