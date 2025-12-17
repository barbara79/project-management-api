<?php

namespace App\Service;

use App\Entity\Project;
use Psr\Log\LoggerInterface;
use Throwable;

class NotificationService
{
// It can be replaced with EmailService etc
    public function __construct(
        private LoggerInterface $logger,
    )
    {}

    public function notify(Project $project)
    {
        $owner = $project->getOwner();
        $title = $project->getTitle();

        try {
            $this->logger->info(sprintf(
                'Notification: Project "%s" has been matched with owner "%s".',
                $title,
                $owner
            ));
        } catch (Throwable ) {
            $this->logger->error(sprintf(
                'Notification: Project "%s" has an invalid owner "%s".',
                $title,
                $owner
            ));
        }

    }
}
