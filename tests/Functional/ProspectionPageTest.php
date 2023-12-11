<?php

namespace App\Tests\Functional;

use App\DataFixtures\UserFixtures;
use App\Entity\Prospection;
use App\Repository\ProspectionRepository;
use App\Tests\Trait\GetUserTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProspectionPageTest extends WebTestCase
{
    use GetUserTrait;

    public function testProspectionIndex(): void
    {
        $client = static::createClient();
        $user = static::getUser('john.doe@gmail.com');

        $client->loginUser($user);
        $crawler = $client->request('GET', '/prospections');
        static::assertResponseIsSuccessful();
    }

    public function testProspectionShow(): void
    {
        $client = static::createClient();
        $user = static::getUser(UserFixtures::USER_JOHN_DOE);
        $prospection = static::getContainer()->get(ProspectionRepository::class)->findOneBy(['user' => $user]);
        static::assertInstanceOf(Prospection::class, $prospection);

        $client->loginUser($user);
        $crawler = $client->request('GET', '/prospection/show/'.(string) $prospection->getId());
        static::assertResponseIsSuccessful();
    }

    public function testProspectionShowForbidden(): void
    {
        $client = static::createClient();
        $marie = static::getUser(UserFixtures::USER_MARIE);
        $john = static::getUser(UserFixtures::USER_JOHN_DOE);
        $prospection = static::getContainer()->get(ProspectionRepository::class)->findOneBy(['user' => $marie]);
        static::assertInstanceOf(Prospection::class, $prospection);

        $client->loginUser($john);
        $crawler = $client->request('GET', '/prospection/show/'.(string) $prospection->getId());
        static::assertResponseStatusCodeSame(403);
    }
}
