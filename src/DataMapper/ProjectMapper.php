<?php

namespace App\DataMapper;

use App\Dto\CreateProjectDTO;
use App\Entity\Project;

class ProjectMapper
{
    public function fromDTO(CreateProjectDTO $projectDTO): Project
    {
         $project = new Project();
         $project->setTitle($projectDTO->title);
         $project->setDescription($projectDTO->description);
         $project->setDeadline(new \DateTimeImmutable($projectDTO->deadline));
         $project->setOwner($projectDTO->owner);

         return $project;
    }
}
