<?php

namespace Tests\Feature\Controller;


use App\Handler\GetProjectByIdHandler;
use Mockery;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;


describe('testing Get Project by id Controller', function () {
    it('returns the project by ID', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $client->request('GET', '/projects/1');

        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_OK);

        $data = json_decode($response->getContent(), true);
        expect($data['title'])->toBe('Project title 1');
        expect($data['description'])->toBe('Project description 1');
    });

    it('returns 404 if the project does not exist', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $client->request('GET', '/projects/9');
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
    });


    it('returns 500  if handler throws an unexpected error', function () {
        /** @var KernelBrowser $client */
        $client = static::createClient();
        $handlerMocked = Mockery::mock(GetProjectByIdHandler::class);
        $handlerMocked->shouldReceive('handle')->andThrow(new \Exception('Internal Server Error'));
        $client->getContainer()->set(GetProjectByIdHandler::class, $handlerMocked);
        $client->request('GET', '/projects/1');
        $response = $client->getResponse();

        expect($response->getStatusCode())->toBe(Response::HTTP_INTERNAL_SERVER_ERROR);

        $data = json_decode($response->getContent(), true);

        expect($data['error'])->toBe('Internal Server Error');
    });
});


