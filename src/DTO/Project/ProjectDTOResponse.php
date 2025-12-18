<?php

namespace App\DTO\Project;

use Symfony\Component\Validator\Constraints as Assert;

class ProjectDTOResponse
{
    public function __construct(
        #[Assert\NotNull]
        public int $projectId,
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotNull]
        public string $description,
        #[Assert\NotBlank]
        public string $deadline,
        public string $owner
    ) {}
}
