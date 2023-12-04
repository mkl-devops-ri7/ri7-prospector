<?php

namespace App\EventListener;

use App\Entity\Prospection;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Prospection::class)]
readonly class ProspectionEntityListener
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function __invoke(Prospection $prospection): void
    {
        if (null !== $prospection->getUser()) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new RuntimeException('User not found');
        }

        $prospection->setUser($user);
    }
}
