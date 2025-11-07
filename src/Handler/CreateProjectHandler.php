<?php

namespace App\Handler;

use App\DataMapper\ProjectMapper;
use App\Dto\CreateProjectDTO;
use App\Dto\ProjectDTOInterface;
use App\Entity\Project;

use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Validator\CreateProjectValidator;


class CreateProjectHandler implements ProjectHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private CreateProjectValidator $validator,
        private NotificationService $notificationService,
    ) {}

    public function handle(Project $projectMapped): Project
    {
        $this->validator->projectValidator($projectMapped);

        $this->em->persist($projectMapped);
        $this->em->flush();

        $this->notificationService->notify($projectMapped);

        return $projectMapped;
    }
}
