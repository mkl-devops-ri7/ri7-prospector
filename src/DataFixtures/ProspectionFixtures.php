<?php

namespace App\DataFixtures;

use App\Entity\Enum\ProspectionTypeEnum;
use App\Entity\Prospection;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use function assert;

class ProspectionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER_JOHN_DOE);
        assert($user instanceof User);

        $faker = Factory::create();

        for ($i = 0; $i < 5; ++$i) {
            $type = $faker->randomElement(ProspectionTypeEnum::cases());
            assert($type instanceof ProspectionTypeEnum);

            $prospection = new Prospection();
            $prospection->setName($faker->jobTitle())
                ->setUser($user)
                ->setComment($faker->text())
                ->setType($type)
            ;

            $manager->persist($prospection);
            $manager->flush();
        }

        $manager->flush();
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
}
