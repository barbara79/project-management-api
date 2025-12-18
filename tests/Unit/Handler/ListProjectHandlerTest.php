<?php

namespace App\Tests\Unit\Handler;

use App\Dto\Project\ProjectDTOResponse;
use App\Exception\NotFoundProjectException;
use App\Handler\Project\ListProjectHandler;
use App\Repository\ProjectRepository;
use Mockery;

afterEach(function () {
    Mockery::close();
});

describe('testing list projects handler', function () {
    it('lists all projects', function () {
        $projectId1 = 1;
        $title1 = 'Project A';
        $description1 = 'Description A';
        $deadline1 = new \DateTime('2025-12-20');
        $owner1 = 'Alice';
        $project1 = makeProject(title: $title1, description: $description1, deadline: $deadline1, owner: $owner1);

        $projectId2 = 2;
        $title2 = 'Project B';
        $description2 = 'Description B';
        $deadline2 = new \DateTime('2025-12-12');
        $owner2 = 'Bob';
        $project2 = makeProject(projectId: $projectId2, title: $title2, description: $description2, deadline: $deadline2, owner: $owner2);

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('findAll')
            ->andReturn([$project1, $project2])
            ->once();

        $handler = new ListProjectHandler($repo);
        $result = $handler->handle();

        expect($result)->toHaveCount(2);
        expect($result[0])->toBeInstanceOf(ProjectDTOResponse::class);
        expect($result[0]->title)->toEqual($title1);
        expect($result[1]->title)->toEqual($title2);
    });

    it('throws exception if no projects have been found', function () {
        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('findAll')
            ->once();
        $handler = new ListProjectHandler($repo);
        $handler->handle();
    })->throws(NotFoundProjectException::class);
});
