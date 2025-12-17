<?php

namespace Tests\Feature\Controller;



use App\Entity\Project;
use App\Handler\UpdateProjectHandler;
use Mockery;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

describe('testing Update Project by id controller', function () {
    it('update a project by id successfully', function () {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $title = 'Integration Test Project';
        $description = 'This is a test project';
        $deadline = new \DateTimeImmutable('2025-12-28');
        $owner = 'Jane Doe';
        $project = new Project();
        $project->setTitle($title);
        $project->setDescription($description);
        $project->setDeadline($deadline);
        $project->setOwner($owner);

        $em->persist($project);
        $em->flush();
        $id = $project->getId();

        $payload = [
            'deadline' => '2025-12-31',
            'owner' => 'John Doe',
        ];

        $client->request(
            'PATCH',
            '/projects/' . $id,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );
        $response = $client->getResponse();
        expect($response->getStatusCode())->toBe(Response::HTTP_OK);

        $em->clear();

        $updatedProject = $em->getRepository(Project::class)->find($id);

        expect($updatedProject)->not()->toBeNull();
        expect($updatedProject->getOwner())->toBe('John Doe');
        expect($updatedProject->getDeadline()->format('Y-m-d'))->toBe('2025-12-31');


        $data = json_decode($response->getContent(), true);

        /// Delete the project at the end
        $client->request('DELETE', '/projects/' . $data['id']);
        $deleteResponse = $client->getResponse();

        expect($deleteResponse->getStatusCode())->toBe(Response::HTTP_OK);
    });

    it('returns 404 if the project does not exist', function () {
        $client = static::createClient();
        $payload = [
            'deadline' => '2025-12-31',
            'owner' => 'John Doe',
        ];

        $client->request(
            'PATCH',
            '/projects/24',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
    });

    it('returns 500  if handler throws an unexpected error', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $handlerMocked = Mockery::mock(UpdateProjectHandler::class);
        $handlerMocked->shouldReceive('handle')->andThrow(new \Exception('Internal Server Error'));
        $client->getContainer()->set(UpdateProjectHandler::class, $handlerMocked);
        $payload = [
            'deadline' => '2025-12-31',
            'owner' => 'John Doe',
        ];
        $client->request(
            'PATCH',
            '/projects/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload));
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_INTERNAL_SERVER_ERROR);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Internal Server Error');
    });
});
