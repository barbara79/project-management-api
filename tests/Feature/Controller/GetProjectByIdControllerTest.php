<?php

namespace Tests\Feature\Controller;


use Symfony\Component\HttpFoundation\Response;


beforeEach(function () {
    $this->client = static::createClient();
});

it('returns the project by ID', function () {
    $this->client->request('GET', '/projects/1');

    $response = $this->client->getResponse();

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);

    $data = json_decode($response->getContent(), true);
    expect($data['title'])->toBe('Project title 1');
    expect($data['description'])->toBe('Project description 1');
});

it('returns an exception if the project does not exist', function () {
    $this->client->request('GET', '/projects/9');
    $response = $this->client->getResponse();

    expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
});



