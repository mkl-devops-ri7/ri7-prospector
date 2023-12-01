<?php

namespace App\Tests\Functional;

use App\Tests\Trait\GetUserTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    use GetUserTrait;

    public function testHomeNoLogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        static::assertResponseIsSuccessful();
    }

    public function testHomeLogged(): void
    {
        $client = static::createClient();
        $client->loginUser(static::getUser('john.doe@gmail.com'));
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#get-started', 'Démarrez votre prospection !');
    }
}
