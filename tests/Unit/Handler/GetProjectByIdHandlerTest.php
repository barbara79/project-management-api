<?php

namespace App\Tests\Unit\Handler;

use App\Dto\GetProjectDTO;
use App\Entity\Project;
use App\Exception\NotFoundProjectException;
use App\Handler\GetProjectByIdHandler;
use App\Repository\ProjectRepository;
use Mockery;


afterEach(function () {
    Mockery::close();
});

describe('testing getProjectById handler', function () {
    it('find project by id successfully', function () {
        $projectId = 24;
        $deadline = new \DateTime('2025-12-01');

        $project = makeProject(projectId: $projectId, deadline: $deadline);

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('find')
            ->with($projectId)
            ->andReturn($project)
            ->once();

        $handler = new GetProjectByIdHandler($repo);
        $result = $handler->handle(GetProjectDTO::from($projectId));

        expect($result->projectId)->toEqual($projectId)
            ->and($result->title)->toEqual('Project title')
            ->and($result->deadline)->toEqual('2025-12-01')
            ->and($result->owner)->toEqual('John Doe');
    });

    it('throws NotFoundProjectException when project does not exist', function () {
        $projectId = 1;

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('find')
            ->with($projectId)
            ->andReturnNull()
            ->once();

        $handler = new GetProjectByIdHandler($repo);
        $handler->handle(GetProjectDTO::from($projectId));
    })->throws(NotFoundProjectException::class);


    it('returns null when deadline is invalid', function () {
        $projectId = 24;
        $project = Mockery::mock(Project::class);
        $project->shouldReceive('getId')->andReturn($projectId);
        $project->shouldReceive('getTitle')->andReturn('Project title');
        $project->shouldReceive('getDescription')->andReturn('Project description');
        $project->shouldReceive('getOwner')->andReturn('John Doe');
        $project->shouldReceive('getDeadline')->andReturnNull();

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('find')
            ->with($projectId)
            ->andReturn($project)
            ->once();

        $handler = new GetProjectByIdHandler($repo);
        $result = $handler->handle(GetProjectDTO::from($projectId));

        expect('')->toEqual($result->deadline);
    });
});
