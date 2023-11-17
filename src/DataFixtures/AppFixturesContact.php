<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixturesContact extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $company1 = $this->getReference('company-1');
        $company2 = $this->getReference('company-2');

        $faker = Factory::create();

        for ($i = 0; $i < 5; ++$i) {
            $contact = new Contact();

            $contact->setEmail($faker->email);
            $jobTitle = substr($faker->jobTitle, 0, 20);
            $contact->setJob($jobTitle);
            $contact->setFirstName($faker->firstName());
            $contact->setLastName($faker->lastName());
            $contact->setLinkedinProfilUrl($faker->url());
            $contact->setPhoneNumber($faker->phoneNumber());
            $randomCompany = $faker->boolean ? $company1 : $company2;
            $contact->setCompany($randomCompany);

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
