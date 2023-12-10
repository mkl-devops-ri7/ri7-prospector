<?php

namespace App\Tests\Api\Contact;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Tests\Trait\GetUserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetContactTest extends ApiTestCase
{
    use GetUserTrait;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testFilters(): void
    {
        $client = static::createClient();
        $contact = static::getContainer()->get(ContactRepository::class)->findOneBy([]);
        static::assertInstanceOf(Contact::class, $contact);

        $client->loginUser(static::getUser('john.doe@gmail.com'));
        $client->request(Request::METHOD_GET, '/api/contacts', [
            'query' => [
                'firstName' => $contact->getFirstName(),
            ],
            'headers' => [
                'content-type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
            'hydra:member' => [
                0 => [
                    'email' => $contact->getEmail(),
                    'job' => $contact->getJob(),
                    'firstName' => $contact->getFirstName(),
                    'lastName' => $contact->getLastName(),
                ],
            ],
        ]);
    }
}
