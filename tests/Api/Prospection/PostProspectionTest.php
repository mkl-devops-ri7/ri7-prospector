<?php

namespace App\Tests\Api\Prospection;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Enum\ProspectionTypeEnum;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PostProspectionTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testSomething(): void
    {
        $client = static::createClient();
        $user = static::getContainer()
            ->get(UserRepository::class)
            ->findOneBy(['email' => 'john.doe@gmail.com']);

        static::assertInstanceOf(UserInterface::class, $user);

        $client->loginUser($user);
        $response = $client->request(Request::METHOD_POST, '/api/prospections', [
            'json' => [
                'comment' => 'Some comment',
                'name' => 'Job application to Google',
                'type' => ProspectionTypeEnum::JobApplication->value,
            ],
            'headers' => [
                'content-type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/']);
    }
}
