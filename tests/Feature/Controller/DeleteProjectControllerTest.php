<?php

namespace Tests\Feature\Controller;


use App\Entity\Project;
use App\Handler\DeleteProjectByIdHandler;
use Mockery;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

describe('testing Delete Project by id Controller', function () {
    it('delete a project successfully', function () {
        /** @var KernelBrowser $client */
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

        $client->request('DELETE', '/projects/' . $id);
        $deleteResponse = $client->getResponse();

        expect($deleteResponse->getStatusCode())->toBe(Response::HTTP_OK);

        $data = json_decode($deleteResponse->getContent(), true);

        expect($data['success'])->toBe('project deleted');

        // Verify deletion in DB
        expect($em->getRepository(\App\Entity\Project::class)->find($id))->toBeNull();
    });

    it('returns 400 if project does not exist', function () {
        $client = static::createClient();

        $client->request('DELETE', '/projects/999999');

        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST);

        $data = json_decode($response->getContent(), true);
        expect($data['error'])->toBe('Project Not Found');
    });

    it('returns 400 since data mapper is failing    ', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();

        $client->request(
            'POST',
            '/projects',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{}'
        );
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_BAD_REQUEST);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Data mapper error');
    });


    it('returns 500  if handler throws an unexpected error', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $handlerMocked = Mockery::mock(DeleteProjectByIdHandler::class);
        $handlerMocked->shouldReceive('handle')->andThrow(new \Exception('Internal Server Error'));
        $client->getContainer()->set(DeleteProjectByIdHandler::class, $handlerMocked);
        $client->request('DELETE', '/projects/1');
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_INTERNAL_SERVER_ERROR);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Internal Server Error');
    });
});



