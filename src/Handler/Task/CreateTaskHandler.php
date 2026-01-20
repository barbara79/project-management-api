<?php

namespace App\Handler\Task;

use App\DataMapper\Task\TaskMapper;
use App\Dto\Task\CreateTaskDTO;
use App\Dto\Task\TaskDTOInterface;
use App\Dto\Task\TaskDTOResponse;
use App\Exception\PersistException;
use Doctrine\ORM\EntityManagerInterface;


class CreateTaskHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private TaskMapper $taskMapper,
    ) {}


    public function handle(?TaskDTOInterface $taskDTO): TaskDTOResponse
    {
        /** @var CreateTaskDTO $taskDTO */
        $task = $this->taskMapper->mapDTOToEntity($taskDTO);

        try {
            $this->em->persist($task);
            $this->em->flush();
        } catch (\Throwable ) {
            throw new PersistException();
        }

        return new TaskDTOResponse(
            taskId: $task->getId(),
            title: $task->getTitle(),
            description: $task->getDescription(),
            status: $task->getStatus(),
        );
    }
}
