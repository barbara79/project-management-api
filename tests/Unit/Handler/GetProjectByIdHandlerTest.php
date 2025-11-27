<?php

namespace App\Tests\Unit\Handler;

use App\Dto\GetProjectDTO;
use App\Entity\Project;
use App\Exception\NotFoundProjectException;
use App\Handler\GetProjectByIdHandler;
use App\Repository\ProjectRepository;
use PHPUnit\Framework\TestCase;

class GetProjectByIdHandlerTest extends TestCase
{
    public function testHandlerReturnHappyCase()
    {
        $projectId = 24;

        $project = new Project();
        $project->setTitle('Project title');
        $project->setDescription('Project description');
        $project->setDeadline(new \DateTime('2025-12-01'));
        $project->setOwner('John Doe');

        $reflector = new \ReflectionClass($project);
        $reflectorProperty = $reflector->getProperty('id');
        $reflectorProperty->setValue($project, $projectId);


        $repo = $this->createMock(ProjectRepository::class);
        $repo->expects($this->once())
            ->method('find')
            ->with($projectId)
            ->willReturn($project);


        $handler = new GetProjectByIdHandler($repo);
        $result = $handler->handle(GetProjectDTO::from($projectId));

        $this->assertSame($projectId, $result->projectId);
        $this->assertSame('Project title', $result->title);
        $this->assertSame('John Doe', $result->owner);
        $this->assertSame('2025-12-01', $result->deadline);

    }

    public function testHandlerNotFoundWhenProjectDoesNotExist()
    {
        $this->expectException(NotFoundProjectException::class);
        $projectId = 1;

        $repo = $this->createMock(ProjectRepository::class);
        $repo->expects($this->once())
            ->method('find')
            ->with($projectId)
            ->willReturn(null);

        $handler = new GetProjectByIdHandler($repo);
        $handler->handle(GetProjectDTO::from($projectId));
    }

    public function testReturnsNullWhenDeadlineIsInvalid()
    {
        $projectId = 24;
        $project = $this->createMock(Project::class);
        $project->method('getId')->willReturn($projectId);
        $project->method('getTitle')->willReturn('Project title');
        $project->method('getDescription')->willReturn('Project description');
        $project->method('getDeadline')->willReturn(null);
        $project->method('getOwner')->willReturn('John Doe');

        $repo = $this->createMock(ProjectRepository::class);
        $repo->expects($this->once())
            ->method('find')
            ->with($projectId)
            ->willReturn($project);

        $handler = new GetProjectByIdHandler($repo);
        $result = $handler->handle(GetProjectDTO::from($projectId));

        $this->assertSame('', $result->deadline);
    }


}

