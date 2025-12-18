<?php

namespace App\DTO\Project;

readonly class GetProjectDTO implements ProjectDTOInterface
{

    public function __construct(
        public int $projectId
    ) {}

    public static function from(int $projectId): self
    {
        return new self(
            $projectId
        );
    }


}
