<?php

use App\DTO\Project\GetProjectDTO;

it('gets DTO project happy case', function () {

    $dto = new GetProjectDTO(1);
    
    expect($dto->projectId)->toBe(1);
});

it('creates GetProjectDTO from factory method', function () {
    $dto = GetProjectDTO::from(42);

    expect($dto)->toBeInstanceOf(GetProjectDTO::class);
    expect($dto->projectId)->toBe(42);
});
