<?php

namespace App\DTO\Project;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateProjectDTO implements ProjectDTOInterface
{
    public function __construct(
        #[Assert\NotBlank(message: "The project title is required.")]
        #[Assert\Length(
            max: 255,
            maxMessage: "The project title cannot exceed {{ limit }} characters."
        )]
        public ?string $title = null,

        #[Assert\Length(
            max: 2000,
            maxMessage: "The description cannot exceed {{ limit }} characters."
        )]
        public ?string $description = null,

        #[Assert\NotBlank]
        #[Assert\Type('string', message: "Deadline must be a valid date string.")]
        public ?string $deadline = null,

        #[Assert\NotBlank(message: "An owner name is required.")]
        public ?string $owner = null,
    )
    {}
}
