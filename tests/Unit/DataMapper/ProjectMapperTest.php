<?php

use App\DataMapper\Project\ProjectMapper;
use App\Dto\Project\CreateProjectDTO;
use App\Entity\Project;
use App\Exception\DataMapperException;
use Symfony\Component\Serializer\SerializerInterface;

afterEach(function () {
    Mockery::close();
});

describe('testing project mapper', function () {
    it('testing  mapDTOToEntity', function () {
        $title = 'Project title';
        $description = 'Project description';
        $owner = 'John Doe';
        $deadlineString = '2025-12-20';

        $dto = new CreateProjectDTO($title, $description, $deadlineString, $owner);

        $serializer = Mockery::mock(SerializerInterface::class);

        $mapper = new ProjectMapper($serializer);
        $result = $mapper->mapDTOToEntity($dto);

        expect($result)->toBeInstanceOf(Project::class)
            ->and($result->getOwner())->toEqual($owner)
            ->and($result->getTitle())->toEqual($title);
    });

    it('mapDTOToEntity throws the DataMapperException in case mapping fails', function () {
        $dto = new CreateProjectDTO();

        $serializer = Mockery::mock(SerializerInterface::class);

        $mapper = new ProjectMapper($serializer);
       $mapper->mapDTOToEntity($dto);

    })->throws(DataMapperException::class);

    it('testing mapRequestToDTO happy case', function () {
        $jsonContent = json_encode([
            'title' => 'Project title',
            'description' => 'Description',
            'deadline' => '2025-12-01',
            'owner' => 'John Doe'
        ]);
        $dto = new CreateProjectDTO(
            'Project title',
            'Description',
            '2025-12-01',
            'John Doe'
        );

        $serializer = Mockery::mock(SerializerInterface::class);
        $serializer->shouldReceive('deserialize')
            ->with($jsonContent, CreateProjectDTO::class, 'json', [])
            ->andReturn($dto);
        $mapper= new ProjectMapper($serializer);
        $result = $mapper->mapRequestToDTO( $jsonContent, CreateProjectDTO::class, []);

        expect($result)->toBeInstanceOf(CreateProjectDTO::class);

        /** @var CreateProjectDTO $result */
        expect($result->title)->toEqual('Project title');
    });

    it('mapRequestToDTO throws DataMapperException in case mapping fails', function () {
        $jsonContent = json_encode([
            'title' => 'Project title',
            'description' => 'Description',
            'deadline' => '2025-12-01',
            'owner' => 'John Doe'
        ]);

        $serializer = Mockery::mock(SerializerInterface::class);
        $serializer->shouldReceive('deserialize')
            ->with($jsonContent, CreateProjectDTO::class, 'json', [])
            ->andThrow(new DataMapperException());

        $mapper= new ProjectMapper($serializer);
        $mapper->mapRequestToDTO($jsonContent, CreateProjectDTO::class, []);

    })->throws(DataMapperException::class);
});
