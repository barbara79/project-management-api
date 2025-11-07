<?php

namespace App\Validator;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;

interface ProjectValidator
{
    public function projectValidator(Project $projectMapped): void;
}
