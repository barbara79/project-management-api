<?php

namespace App\Tests\Unit\Handler;

use App\Dto\GetProjectDTO;
use App\Exception\NotFoundProjectException;
use App\Exception\PersistException;
use App\Handler\DeleteProjectByIdHandler;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;

afterEach(function () {
    Mockery::close();
});

describe('testing delete project by id handler', function () {
    it('deletes project by id', function () {
        $projectId = 1;
        $deadline = new \DateTime('2025-12-15');
        $project = makeProject(deadline: $deadline);

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('find')
            ->with($projectId)
            ->once()
            ->andReturn($project);

        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('remove')
            ->with($project)
            ->once();
        $em->shouldReceive('flush')
            ->once();

        $projectDTO = GetProjectDTO::from($projectId);

        $handler = new DeleteProjectByIdHandler($repo, $em);
        $result =$handler->handle($projectDTO);

        expect($result)->toBeNull();
    });

    it('throws NotFoundProjectException when tries to find a project', function () {
        $projectId = 1;
        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('find')
            ->with($projectId)
            ->once();

        $em = Mockery::mock(EntityManagerInterface::class);

        $projectDTO = GetProjectDTO::from($projectId);
        $handler = new DeleteProjectByIdHandler($repo, $em);
        $handler->handle($projectDTO);
    })->throws(NotFoundProjectException::class);

    it('throws PersistException if it fails during the flush', function () {
        $projectId = 1;
        $deadline = new \DateTime('2025-12-15');
        $project = makeProject(deadline: $deadline);

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('find')
            ->with($projectId)
            ->andReturn($project)
            ->once();
        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('remove')
            ->with($project)
            ->once();
        $em->shouldReceive('flush')
            ->once()
            ->andThrow(new PersistException());

        $projectDTO = GetProjectDTO::from($projectId);
        $handler = new DeleteProjectByIdHandler($repo, $em);
        $handler->handle($projectDTO);
    })->throws(PersistException::class);
});
