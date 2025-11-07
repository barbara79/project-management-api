<?php

namespace App\Handler;

use App\DataMapper\ProjectMapper;
use App\Dto\ProjectDTOInterface;
use App\Entity\Project;

interface ProjectHandlerInterface
{
    public function handle(Project $projectMapped): Project;
}
