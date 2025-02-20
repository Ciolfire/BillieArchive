<?php

namespace App\Tests\Controller\Vampire;

use App\Controller\Vampire\DevotionController;
use App\Entity\Devotion;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;


#[CoversClass(DevotionController::class)]
#[CoversClass(Devotion::class)]
class DevotionTest extends WebTestCase
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
    $client->request('GET', '/en/vampire/wiki/devotions');
    self::assertResponseIsSuccessful();
  }

  public function testList(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/devotions/list/book/2');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/vampire/wiki/devotions/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/devotion/1');
    self::assertResponseIsSuccessful();
  }

  public function testNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/devotion/new');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/devotion/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testDelete(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/devotion/1/delete');
    self::assertResponseRedirects();
  }
}
