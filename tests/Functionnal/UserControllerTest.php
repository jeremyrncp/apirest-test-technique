<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Tests\Functionnal;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UserControllerTest extends WebTestCase
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->serializer = $kernel->getContainer()->get('serializer');
    }

    public function testMustObtainAnErrorWhenUserIsAlreadyRegistred()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($this->getUserWithEmailAlreadyused())
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainASuccessWhenUserIsRegistred()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($this->getUserWithValidInformations())
        );

        $user =  $this->serializer->deserialize($client->getResponse()->getContent(), User::class, 'json');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenBirthDateIsntValid()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($this->getUserWithInvalidBirthDate())
        );
        $this->assertEquals('{"msg":"Birth date isn\'t valid"}', $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testMustObtainAnErrorWhenEmailIsntValid()
    {
        $client = static::createClient();

        $client->request('POST', '/api/user',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($this->getUserWithInvalidEmail())
        );
        $this->assertEquals('{"msg":"This email isn\'t valid"}', $client->getResponse()->getContent());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    private function getUserWithEmailAlreadyused(): array
    {
        return [
            'username' => 'test',
            'email' => 'martin.dupont@api.com',
            'birthDate' => '2018-01-01'
        ];
    }

    private function getUserWithValidInformations(): array
    {
        return [
            'username' => 'test',
            'email' => 'test@user.com',
            'birthDate' => '2018-01-01'
        ];
    }

    private function getUserWithInvalidBirthDate(): array
    {
        return [
            'username' => 'test',
            'email' => 'test@user.com',
            'birthDate' => '201855-01-01'
        ];
    }

    private function getUserWithInvalidEmail(): array
    {
        return [
            'username' => 'test',
            'email' => 'test_at_user.error',
            'birthDate' => '2018-01-01'
        ];
    }
}
