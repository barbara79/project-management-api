<?php

namespace App\Handler;

use App\DataMapper\ProjectMapper;
use App\Dto\ProjectDTOInterface;
use App\Dto\ProjectDTOResponse;
use App\Exception\PersistException;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;


class CreateProjectHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private NotificationService $notificationService,
        private ProjectMapper $projectMapper,
    ) {}


    public function handle(?ProjectDTOInterface $projectDTO): ProjectDTOResponse
    {
        $project = $this->projectMapper->mapDTOToEntity($projectDTO);

        try {
            $this->em->persist($project);
            $this->em->flush();
        } catch (\Throwable ) {
            throw new PersistException();
        }

        $this->notificationService->notify($project);

        return new ProjectDTOResponse(
            projectId: $project->getId(),
            title: $project->getTitle(),
            deadline: $project->getDeadline()?->format('Y-m-d') ?? '',
            owner: is_object($project->getOwner()) ? (string) $project->getOwner() : $project->getOwner()
        );
    }
}
