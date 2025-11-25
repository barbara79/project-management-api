<?php

namespace App\Handler;

use App\DataMapper\ProjectMapper;
use App\Dto\ProjectDTOInterface;
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


    public function handle(?ProjectDTOInterface $projectDTO): null
    {
        $project = $this->projectMapper->mapDTOToEntity($projectDTO);

        try {
            $this->em->persist($project);
            $this->em->flush();
        } catch (\Throwable ) {
            throw new PersistException();
        }

        $this->notificationService->notify($project);

        //TODO how to return project with ID
        return null;
    }
}
