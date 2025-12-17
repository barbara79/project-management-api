<?php

namespace App\DataMapper;

use App\Dto\CreateProjectDTO;
use App\Dto\ProjectDTOInterface;
use App\Entity\Project;
use App\Exception\DataMapperException;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectMapper
{
    public function __construct(private SerializerInterface $serializer)
    {}

    public function mapDTOToEntity(CreateProjectDTO $projectDTO): Project
    {
        try {
            $project = new Project();
            $project->setTitle($projectDTO->title);
            $project->setDescription($projectDTO->description);
            $project->setDeadline(new \DateTimeImmutable($projectDTO->deadline));
            $project->setOwner($projectDTO->owner);

            return $project;
        } catch (\Throwable) {
            throw new DataMapperException();
        }

    }

    public function mapRequestToDTO(string $content, string $classType, array $context = []): ProjectDTOInterface
    {
        try {
            return $this->serializer->deserialize($content, $classType, 'json', $context);
        } catch (\Throwable ) {
            throw new DataMapperException();
        }
    }
}
