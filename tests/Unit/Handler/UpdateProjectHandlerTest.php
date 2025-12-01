<?php

namespace App\Tests\Unit\Handler;

use App\Dto\GetProjectDTO;
use App\Dto\UpdateProjectDTO;
use App\Exception\NotFoundProjectException;
use App\Exception\PersistException;
use App\Handler\UpdateProjectHandler;
use App\Repository\ProjectRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;

afterEach(function () {
    Mockery::close();
});


describe('testing update project by id handler', function () {
   it('updates project by id with new title successfully', function () {
       $projectId = 24;

       $project = makeProject(projectId: $projectId);

       $repo = Mockery::mock(ProjectRepository::class);
       $repo->shouldReceive('find')
           ->with($projectId)
           ->andReturn($project)
           ->once();
       $entityManager = Mockery::mock(EntityManagerInterface::class);
       $entityManager->shouldReceive('persist')
           ->with($project)
           ->once();
       $entityManager->shouldReceive('flush')
           ->once();

       $notificationService = Mockery::mock(NotificationService::class);
       $notificationService->shouldReceive('notify')
           ->with($project);

       $handler = new UpdateProjectHandler($repo, $entityManager, $notificationService);
       $getProjectDTO = GetProjectDTO::from($projectId);
       $newTitle = 'new Title';
       $updateProjectDTO = new UpdateProjectDTO($newTitle);

       $result = $handler->handle($getProjectDTO, $updateProjectDTO);

       expect($result->title)->toEqual($newTitle);
   });

   it('throws NotFoundProjectException if it does not find the project', function () {
       $projectId = 1;

       $repo = Mockery::mock(ProjectRepository::class);
       $repo->shouldReceive('find')
           ->with($projectId)
           ->andReturnNull()
           ->once();
       $entityManager = Mockery::mock(EntityManagerInterface::class);

       $notificationService = Mockery::mock(NotificationService::class);

       $updateProjectDTO = new UpdateProjectDTO();
       $handler = new UpdateProjectHandler($repo, $entityManager, $notificationService);
       $getProjectDTO = GetProjectDTO::from($projectId);
       $handler->handle($getProjectDTO, $updateProjectDTO);
   })->throws(NotFoundProjectException::class);

   it('throws PersistException if it fails during the flush', function () {
       $projectId = 24;

       $project = makeProject(projectId: $projectId);
       $repo = Mockery::mock(ProjectRepository::class);
       $repo->shouldReceive('find')
           ->with($projectId)
           ->andReturn($project)
           ->once();
       $entityManager = Mockery::mock(EntityManagerInterface::class);
       $entityManager->shouldReceive('persist')
           ->with($project)
           ->once();
       $entityManager->shouldReceive('flush')
           ->once()
           ->andThrow(new PersistException());

       $notificationService = Mockery::mock(NotificationService::class);
       $notificationService->shouldReceive('notify')
           ->with($project);

       $handler = new UpdateProjectHandler($repo, $entityManager, $notificationService);
       $getProjectDTO = GetProjectDTO::from($projectId);
       $newTitle = 'new Title';
       $updateProjectDTO = new UpdateProjectDTO($newTitle);

       $handler->handle($getProjectDTO, $updateProjectDTO);
   })->throws(PersistException::class);
});
