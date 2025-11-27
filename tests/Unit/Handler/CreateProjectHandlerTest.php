<?php

namespace App\Tests\Unit\Handler;

use App\DataMapper\ProjectMapper;
use App\Dto\CreateProjectDTO;
use App\Entity\Project;
use App\Exception\PersistException;
use App\Handler\CreateProjectHandler;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateProjectHandlerTest extends TestCase
{
    public function testCreateProjectHappyCase()
    {
        $title = 'Project title';
        $description = 'Project description';
        $deadline = new \DateTime('2025-12-01');
        $owner = 'John Doe';

        $dto = new CreateProjectDTO($title, $description, '2025-12-01', $owner);

        $projectId = 24;

        $project = new Project();
        $project->setTitle($title);
        $project->setDescription($description);
        $project->setDeadline($deadline);
        $project->setOwner($owner);

        $reflector = new \ReflectionClass($project);
        $reflectorProperty = $reflector->getProperty('id');
        $reflectorProperty->setValue($project, $projectId);

        $mapper = $this->createMock(ProjectMapper::class);
        $mapper->expects($this->once())
            ->method('mapDTOToEntity')
            ->with($dto)
            ->willReturn($project);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('persist')
            ->with($project);
        $em->method('flush');
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('notify')
            ->with($project);

        $handler = new CreateProjectHandler($em, $notificationService, $mapper);
        $result = $handler->handle($dto);

        $this->assertSame($projectId, $result->projectId);
        $this->assertSame($title, $result->title);
        $this->assertSame($owner, $result->owner);
        $this->assertSame('2025-12-01', $result->deadline);
    }

    public function testPersistException()
    {
        $this->expectException(PersistException::class);
        $title = 'Project title';
        $description = 'Project description';
        $deadline = new \DateTime('2025-12-01');
        $owner = 'John Doe';

        $dto = new CreateProjectDTO($title, $description, '2025-12-01', $owner);

        $projectId = 24;

        $project = new Project();
        $project->setTitle($title);
        $project->setDescription($description);
        $project->setDeadline($deadline);
        $project->setOwner($owner);

        $reflector = new \ReflectionClass($project);
        $reflectorProperty = $reflector->getProperty('id');
        $reflectorProperty->setValue($project, $projectId);

        $mapper = $this->createMock(ProjectMapper::class);
        $mapper->expects($this->once())
            ->method('mapDTOToEntity')
            ->with($dto)
            ->willReturn($project);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('persist')
            ->with($project);
        $em->method('flush')
            ->willThrowException(new PersistException());

        $notificationService = $this->createMock(NotificationService::class);

        $handler = new CreateProjectHandler($em, $notificationService, $mapper);
        $handler->handle($dto);
    }
}
