<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        readonly private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@ri7.fr');
        $user->setFirstname('admin');
        $user->setLastname('admin');
        $user->setPassword($this->hasher->hashPassword($user, 'azerty'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $manager->persist($user);
        $manager->flush();
    }
}