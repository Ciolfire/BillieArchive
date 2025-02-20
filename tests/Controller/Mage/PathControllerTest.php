<?php

namespace App\Tests\Controller;

use App\Controller\Mage\PathController;
use App\Entity\Path;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PathController::class)]
#[CoversClass(Path::class)]
final class PathControllerTest extends WebTestCase
{
  // retrieve the test user
  public function getUser(): User
  {
    $userRepository = static::getContainer()->get(UserRepository::class);
    return $userRepository->findOneByUsername('Ciol');
  }

  public function testIndex(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/paths');
    self::assertResponseIsSuccessful();
  }

  public function testList(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/paths/list/book/1');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/mage/wiki/paths/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/path/1');
    self::assertResponseIsSuccessful();
  }

  public function testNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/mage/path/new');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/mage/path/1/edit');
    self::assertResponseIsSuccessful();
  }
}
