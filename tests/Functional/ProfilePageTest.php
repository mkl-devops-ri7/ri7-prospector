<?php

namespace App\Tests\Functional;

use App\Tests\Trait\GetUserTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfilePageTest extends WebTestCase
{
    use GetUserTrait;

    public function testHomeLogged(): void
    {
        $client = static::createClient();
        $user = static::getUser('john.doe@gmail.com');
        $client->loginUser($user);
        $crawler = $client->request('GET', '/profile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form h3', 'User Profile');
        $this->assertInputValueSame('user[email]', (string) $user->getEmail());
        $this->assertInputValueSame('user[firstname]', (string) $user->getFirstname());
        $this->assertInputValueSame('user[lastname]', (string) $user->getLastname());
        $this->assertInputValueSame('user[linkedinProfilUrl]', (string) $user->getLinkedinProfilUrl());
    }
}
