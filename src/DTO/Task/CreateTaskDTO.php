<?php

namespace App\DTO\Task;

use App\Enum\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateTaskDTO implements TaskDTOInterface
{
    public function __construct(
        #[Assert\NotBlank(message: "The task title is required.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "The task title cannot exceed {{ limit }} characters."
        )]
        public ?string $title = null,

        #[Assert\Length(
            max: 2000,
            maxMessage: "The description cannot exceed {{ limit }} characters."
        )]
        public ?string $description = null,

        #[Assert\NotBlank]
        #[Assert\Type(TaskStatus::class, message: "Status must be valid.")]
        public ?string $status = null,
    )
    {}
}
