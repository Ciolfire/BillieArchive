<?php

namespace App\Tests\Controller\Vampire;

use App\Controller\Vampire\ClanController;
use App\Entity\Clan;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;


#[CoversClass(ClanController::class)]
#[CoversClass(Clan::class)]
class ClanTest extends WebTestCase
{
  // retrieve the test user
  public function getUser(): User
  {
    $userRepository = static::getContainer()->get(UserRepository::class);
    return $userRepository->findOneByUsername('Ciol');
  }

  public function testClans(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/clans');
    self::assertResponseIsSuccessful();
  }

  public function testBloodlines(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/bloodlines');
    self::assertResponseIsSuccessful();
  }

  public function testList(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/clans/list/book/2');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/vampire/wiki/clans/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/clan/1');
    self::assertResponseIsSuccessful();
  }

  public function testNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/clan/0/0/new');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/clan/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testDelete(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/clan/1/delete');
    self::assertResponseRedirects();
  }

  public function testJoin(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/1/bloodline/join');
    self::assertResponseIsSuccessful();
  }
}
