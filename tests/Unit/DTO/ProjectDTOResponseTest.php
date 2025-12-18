<?php

use App\DTO\Project\ProjectDTOResponse;



it('creates response DTO project happy case', function () {
    $title = 'Unit Test Title';
    $description = 'Unit Test Description';
    $deadline = '2026-01-15';
    $owner = 'John Doe';
    $dto = new ProjectDTOResponse(1, $title, $description, $deadline, $owner);
    $result = validate($dto);

    expect($result)->toBeEmpty();
});

it('creates DTO project title is invalid', function () {
    $title = '';
    $description = 'Unit Test Description';
    $deadline = '2026-01-15';
    $owner = 'John Doe';
    $dto = new ProjectDTOResponse(1,$title, $description, $deadline, $owner);
    $result = validate($dto);

    expect($result[0]->getMessage())->toBe('This value should not be blank.');
});