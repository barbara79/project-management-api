<?php

namespace Tests\Feature\Controller;


use App\Exception\NotFoundProjectException;
use App\Handler\ListProjectHandler;
use Mockery;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

describe('testing List Projects Controller', function () {
    it('returns all the projects', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $client->request('GET', '/projects');
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_OK);

        $data = json_decode($response->getContent(), true);

        expect(count($data))->toBe(2);
    });

    it('returns 404 if dont exist any projects', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $handlerMocked = Mockery::mock(ListProjectHandler::class);
        $handlerMocked->shouldReceive('handle')->andThrow(new NotFoundProjectException());
        $client->getContainer()->set(ListProjectHandler::class, $handlerMocked);
        $client->request('GET', '/projects');
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Project Not Found');
    });

    it('returns 500  if handler throws an unexpected error', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $handlerMocked = Mockery::mock(ListProjectHandler::class);
        $handlerMocked->shouldReceive('handle')->andThrow(new \Exception('Internal Server Error'));
        $client->getContainer()->set(ListProjectHandler::class, $handlerMocked);
        $client->request('GET', '/projects');
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_INTERNAL_SERVER_ERROR);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Internal Server Error');
    });
});
