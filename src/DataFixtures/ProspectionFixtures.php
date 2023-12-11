<?php

namespace App\DataFixtures;

use App\Entity\Enum\ProspectionTypeEnum;
use App\Entity\Prospection;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use function assert;

class ProspectionFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $john = $this->getReference(UserFixtures::USER_JOHN_DOE);
        assert($john instanceof User);
        for ($i = 0; $i < 5; ++$i) {
            $this->createProspection($john);
        }

        $marie = $this->getReference(UserFixtures::USER_MARIE);
        assert($marie instanceof User);
        for ($i = 0; $i < 5; ++$i) {
            $this->createProspection($marie);
        }
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function createProspection(User $user): Prospection
    {
        $type = $this->faker->randomElement(ProspectionTypeEnum::cases());
        assert($type instanceof ProspectionTypeEnum);

        $prospection = new Prospection();
        $prospection->setName($this->faker->jobTitle())
            ->setUser($user)
            ->setComment($this->faker->text())
            ->setType($type);

        $this->entityManager->persist($prospection);
        $this->entityManager->flush();

        return $prospection;
    }
}
