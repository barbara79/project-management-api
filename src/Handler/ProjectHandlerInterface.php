<?php

namespace App\Handler;


use App\Dto\ProjectDTOInterface;
use App\Dto\ProjectDTOResponse;


interface ProjectHandlerInterface
{
    public function handle(?ProjectDTOInterface $projectDTO): ?ProjectDTOResponse;
}
