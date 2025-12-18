<?php

namespace Tests\Feature\Controller;


use App\Handler\Project\CreateProjectHandler;
use Mockery;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

describe('testing Create Project Controller', function () {
    it('create a new project successfully', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();

        $payload = [
            'title' => 'Integration Test Project ' . uniqid(),
            'description' => 'This is a test project',
            'deadline' => '2025-12-31',
            'owner' => 'Jane Doe',
        ];

        $client->request(
            'POST',
            '/projects',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );
        $response = $client->getResponse();
        expect($response->getStatusCode())->toBe(Response::HTTP_CREATED);

        $data = json_decode($response->getContent(), true);
        expect($data)->toHaveKey('id');

/// Delete the project at the end
        $client->request('DELETE', '/projects/' . $data['id']);
        $deleteResponse = $client->getResponse();

        expect($deleteResponse->getStatusCode())->toBe(Response::HTTP_OK);
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
        $handlerMocked = Mockery::mock(CreateProjectHandler::class);
        $handlerMocked->shouldReceive('handle')->andThrow(new \Exception('Internal Server Error'));
        $client->getContainer()->set(CreateProjectHandler::class, $handlerMocked);
        $client->request(
            'POST',
            '/projects',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['title' => 'Test', 'description' => 'Test']));
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_INTERNAL_SERVER_ERROR);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Internal Server Error');
    });
});



