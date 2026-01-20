<?php

namespace App\DTO\Task;

use Symfony\Component\Validator\Constraints as Assert;

class TaskDTOResponse
{
    public function __construct(
        #[Assert\NotNull]
        public int $taskId,
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotNull]
        public string $description,
        #[Assert\NotBlank]
        public string $status,
    ) {}
}
