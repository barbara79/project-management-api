<?php

namespace Tests\Unit\Service;


use App\Service\NotificationService;
use Mockery;
use Psr\Log\LoggerInterface;

afterEach(function () {
    Mockery::close();
});

describe('testing notification service', function () {
    it('logs info when notification is successful', function () {
        $project = makeProject();
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('info')
            ->with('Notification: Project "Project title" has been matched with owner "John Doe".')
            ->once();

        $logger->shouldNotReceive('error');

        $notificationService = new NotificationService($logger);
        $notificationService->notify($project);
    });

    it('logs give back an error ', function () {
        $project = makeProject();
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('info')
            ->once()
            ->andThrow(new \Exception('Logger failed'));
        $logger->shouldReceive('error')
            ->once()
            ->with('Notification: Project "Project title" has an invalid owner "John Doe".');

        $notificationService = new NotificationService($logger);
        $notificationService->notify($project);
    });
});
