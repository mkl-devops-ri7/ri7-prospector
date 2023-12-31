<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $company = new Company();
        $company->setSiret('123456789123456');
        $company->setEmail('mycompany@gmail.com');
        $company->setLinkedInProfilUrl('https://fr.linkedin.com/');
        $company->setName($faker->company());
        $company->setLogoUrl('https://upload.wikimedia.org/wikipedia/commons/8/85/Logo-Test.png');
        $manager->persist($company);
        $manager->flush();
        $this->addReference('company-1', $company);

        $companyTwo = new Company();
        $companyTwo->setSiret('789456123789456');
        $companyTwo->setEmail('mycompany2@gmail.com');
        $companyTwo->setLinkedInProfilUrl('https://fr.linkedin.com/');
        $companyTwo->setName($faker->company());
        $companyTwo->setLogoUrl('https://upload.wikimedia.org/wikipedia/commons/8/85/Logo-Test.png');
        $manager->persist($company);

        $manager->flush();
        $this->addReference('company-2', $companyTwo);
    }
}
