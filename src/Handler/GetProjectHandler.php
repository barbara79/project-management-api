<?php

namespace App\Handler;

use App\Dto\ProjectDTOInterface;
use App\Entity\Project;
use App\Repository\ProjectRepository;

class GetProjectHandler implements ProjectHandlerInterface
{

    public function __construct(
        readonly ProjectRepository $projectRepository)
    {
    }

    public function handle(?ProjectDTOInterface $projectDTO):  void
    {
       // return $this->projectRepository->findAll();
    }
}
