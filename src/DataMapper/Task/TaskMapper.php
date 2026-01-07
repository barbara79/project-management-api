<?php

namespace App\DataMapper\Task;

use App\Dto\Task\CreateTaskDTO;
use App\Dto\Task\TaskDTOInterface;
use App\Entity\Task;
use App\Exception\DataMapperException;
use Symfony\Component\Serializer\SerializerInterface;

class TaskMapper
{
    public function __construct(private SerializerInterface $serializer)
    {}

    public function mapDTOToEntity(CreateTaskDTO $taskDTO): Task
    {
        try {
            $task = new Task();
            $task->setTitle($taskDTO->title);
            $task->setDescription($taskDTO->description);
            $task->setStatus($taskDTO->status);

            return $task;
        } catch (\Throwable) {
            throw new DataMapperException();
        }

    }

    public function mapRequestToDTO(string $content, string $classType, array $context = []): TaskDTOInterface
    {
        try {
            return $this->serializer->deserialize($content, $classType, 'json', $context);
        } catch (\Throwable ) {
            throw new DataMapperException();
        }
    }
}
