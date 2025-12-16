<?php

//putenv('SYMFONY_DEPRECATIONS_HELPER=weak_vendors'); // silence vendor deprecations
//error_reporting(E_ALL & ~E_USER_DEPRECATED & ~E_DEPRECATED);

use App\Entity\Project;
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
| Helper Functions
|--------------------------------------------------------------------------
|
| Define reusable helpers like `makeProject()` for test data creation.
|
*/

/**
 * Create a Project entity for testing purposes.
 *
 * @param int $projectId
 * @param string $title
 * @param string $description
 * @param DateTime $deadline
 * @param string $owner
 * @return Project
 */
function makeProject(
    int $projectId = 1,
    string $title = "Project title",
    string $description = "Project description",
    DateTime $deadline = new DateTime('2025-12-20'),
    string $owner = 'John Doe'
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
