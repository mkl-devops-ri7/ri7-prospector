<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const string USER_JOHN_DOE = 'john.doe@gmail.com';
    public const string USER_MARIE = 'marie.blanche@gmail.com';
    public const string PLAIN_PASSWORD = 'azerty';

    public function __construct(
        readonly private UserPasswordHasherInterface $hasher,
    ) {
    }

    /**
     * @param array<string> $roles
     */
    private function createUser(
        string $email,
        string $firstName,
        string $lastName,
        array $roles = []): User
    {
        $user = new User();
        $user->setEmail($email)
            ->setFirstname($firstName)
            ->setLastname($lastName)
            ->setPassword($this->hasher->hashPassword(
                user: $user,
                plainPassword: self::PLAIN_PASSWORD
            ))
            ->setRoles($roles)
            ->setIsVerified(true)
        ;

        return $user;
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createUser(
            email: 'admin@ri7.fr',
            firstName: 'Admin',
            lastName: 'Admin', roles: ['ROLE_ADMIN']
        ));

        $john = $this->createUser(
            email: self::USER_JOHN_DOE,
            firstName: 'John',
            lastName: 'Doe'
        );

        $manager->persist($john);
        $this->addReference(self::USER_JOHN_DOE, $john);

        $marie = $this->createUser(
            email: self::USER_MARIE,
            firstName: 'Blanche',
            lastName: 'Marie'
        );

        $manager->persist($marie);
        $this->addReference(self::USER_MARIE, $marie);

        $manager->flush();
    }
}
