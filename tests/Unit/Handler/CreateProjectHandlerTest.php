<?php

namespace App\Tests\Unit\Handler;

use App\DataMapper\ProjectMapper;
use App\Dto\CreateProjectDTO;
use App\Entity\Project;
use App\Exception\PersistException;
use App\Handler\CreateProjectHandler;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;

afterEach(function () {
    Mockery::close();
});


describe('testing createProject handler', function () {
    it('creates project successfully', function () {
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

        $mapper = Mockery::mock(ProjectMapper::class);
        $mapper->shouldReceive('mapDTOToEntity')
            ->with($dto)
            ->andReturn($project)
            ->once();
        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')
            ->with($project)
            ->once();
        $em->shouldReceive('flush');

        $notificationService = Mockery::mock(NotificationService::class);
        $notificationService->shouldReceive('notify')
            ->with($project)
            ->once();

        $handler = new CreateProjectHandler($em, $notificationService, $mapper);
        $result = $handler->handle($dto);

        expect($result->projectId)->toEqual($projectId)
            ->and($result->title)->toEqual($title)
            ->and($result->description)->toEqual($description)
            ->and($result->deadline)->toEqual('2025-12-01')
            ->and($result->owner)->toEqual($owner);
    });

    it('throw PersistException when it tries to flush', function () {
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

        $mapper = Mockery::mock(ProjectMapper::class);
        $mapper->shouldReceive('mapDTOToEntity')
            ->with($dto)
            ->andReturn($project)
            ->once();
        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')
            ->with($project)
            ->once();
        $em->shouldReceive('flush')
            ->once()
            ->andThrow(new PersistException());

        $notificationService = Mockery::mock(NotificationService::class);

        $handler = new CreateProjectHandler($em, $notificationService, $mapper);
        $handler->handle($dto);
    })->throws(PersistException::class);
});

