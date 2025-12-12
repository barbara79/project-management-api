<?php

namespace Tests\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $project = new Project();
         $project->setTitle('Project title 1');
         $project->setDescription('Project description 1');
         $project->setDeadline(new \DateTimeImmutable('2025-12-12'));
         $project->setOwner('John Doe');
         $manager->persist($project);

        $project = new Project();
        $project->setTitle('Project title 2');
        $project->setDescription('Project description 2');
        $project->setDeadline(new \DateTimeImmutable('2025-12-12'));
        $project->setOwner('Alice');
        $manager->persist($project);

        $manager->flush();
    }
}
