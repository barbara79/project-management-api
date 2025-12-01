<?php

namespace App\Tests\Unit\Handler;

use App\Dto\ProjectDTOResponse;
use App\Entity\Project;
use App\Exception\NotFoundProjectException;
use App\Handler\ListProjectHandler;
use App\Repository\ProjectRepository;
use Mockery;

afterEach(function () {
    Mockery::close();
});

describe('testing list projects handler', function () {
    it('lists all projects', function () {
        $projectId1 = 1;
        $project1 = new Project();
        $project1->setTitle('Project A');
        $project1->setDescription('Description A');
        $project1->setDeadline(new \DateTime('2025-01-01'));
        $project1->setOwner('Alice');

        $reflector = new \ReflectionClass($project1);
        $reflectorProperty = $reflector->getProperty('id');
        $reflectorProperty->setValue($project1, $projectId1);

        $projectId2 = 2;
        $project2 = new Project();
        $project2->setTitle('Project B');
        $project2->setDescription('Description B');
        $project2->setDeadline(new \DateTime('2025-02-01'));
        $project2->setOwner('Bob');

        $reflector = new \ReflectionClass($project2);
        $reflectorProperty = $reflector->getProperty('id');
        $reflectorProperty->setValue($project2, $projectId2);

        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('findAll')
            ->andReturn([$project1, $project2])
            ->once();

        $handler = new ListProjectHandler($repo);
        $result = $handler->handle();

        expect($result)->toHaveCount(2);
        expect($result[0])->toBeInstanceOf(ProjectDTOResponse::class);
        expect($result[0]->title)->toEqual('Project A');
        expect($result[1]->title)->toEqual('Project B');
    });

    it('throws exception if no projects have been found', function () {
        $repo = Mockery::mock(ProjectRepository::class);
        $repo->shouldReceive('findAll')
            ->once();
        $handler = new ListProjectHandler($repo);
        $handler->handle();
    })->throws(NotFoundProjectException::class);
});
