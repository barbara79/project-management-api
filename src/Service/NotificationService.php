<?php

namespace App\Service;

use App\Entity\Project;
use Psr\Log\LoggerInterface;

class NotificationService
{
// It can be updated with EmailService etc
    public function __construct(
        private LoggerInterface $logger,
    )
    {
    }

    public function notify(Project $project)
    {
        $owner = $project->getOwner();
        $title = $project->getTitle();

        $this->logger->info(sprintf(
            'Notification: Project "%s" has been matched with owner "%s".',
            $title,
            $owner
        ));
    }
}
