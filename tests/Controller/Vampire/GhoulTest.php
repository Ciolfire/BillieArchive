<?php

namespace App\Tests\Controller\Vampire;

use App\Controller\Vampire\GhoulController;
use App\Entity\Ghoul;
use App\Entity\GhoulFamily;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;


#[CoversClass(GhoulController::class)]
#[CoversClass(Ghoul::class)]
#[CoversClass(GhoulFamily::class)]
class GhoulTest extends WebTestCase
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
    $client->request('GET', '/en/ghoul/wiki/families');
    self::assertResponseIsSuccessful();
  }

  public function testList(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/ghoul/wiki/families/list/book/2');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/ghoul/wiki/families/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/ghoul/wiki/family/1');
    self::assertResponseIsSuccessful();
  }

  public function testNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/ghoul/family/new');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/ghoul/family/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testDelete(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/ghoul/family/1/delete');
    self::assertResponseRedirects();
  }
}
