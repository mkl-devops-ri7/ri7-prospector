<?php

namespace App\Tests\Functional;

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
        $crawler = $client->request('GET', '/prospection');
    }

    public function testProspectionShow(): void
    {
        $client = static::createClient();
        $user = static::getUser('john.doe@gmail.com');
        $prospection = static::getContainer()->get(ProspectionRepository::class)->findOneBy(['user' => $user]);
        static::assertInstanceOf(Prospection::class, $prospection);

        $client->loginUser($user);
        $crawler = $client->request('GET', '/prospection/show/'.(string) $prospection->getId());
    }
}
