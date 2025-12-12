<?php

//putenv('SYMFONY_DEPRECATIONS_HELPER=weak_vendors'); // silence vendor deprecations
//error_reporting(E_ALL & ~E_USER_DEPRECATED & ~E_DEPRECATED);

use App\Entity\Project;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

uses(WebTestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function makeProject(
    $projectId = 1,
    $title = "Project title",
    $description = "Project description",
    $deadline = new DateTime('2025-12-20'),
    $owner = 'John Doe'
) : Project
{
    $project = new Project();
    $project->setTitle($title);
    $project->setDescription($description);
    $project->setDeadline($deadline);
    $project->setOwner($owner);

    $reflector = new \ReflectionClass($project);
    $reflectorProperty = $reflector->getProperty('id');
    $reflectorProperty->setValue($project, $projectId);

    return $project;
}
