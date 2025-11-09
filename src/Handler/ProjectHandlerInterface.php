<?php

namespace App\Handler;


use App\Dto\ProjectDTOInterface;
use App\Entity\Project;

interface ProjectHandlerInterface
{
    public function handle(?ProjectDTOInterface $projectDTO): void;
}
