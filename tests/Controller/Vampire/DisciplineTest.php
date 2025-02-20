<?php

namespace App\Tests\Controller\Vampire;

use App\Controller\Vampire\DisciplineController;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;


#[CoversClass(DisciplineController::class)]
#[CoversClass(Discipline::class)]
#[CoversClass(DisciplinePower::class)]
class DisciplineTest extends WebTestCase
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
    $client->request('GET', '/en/vampire/wiki/disciplines');
    self::assertResponseIsSuccessful();
  }

  public function testSorceriesIndex(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/sorceries');
    self::assertResponseIsSuccessful();
  }

  public function testCoilsIndex(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/coils');
    self::assertResponseIsSuccessful();
  }

  public function testThaumaturgyIndex(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/thaumaturgy');
    self::assertResponseIsSuccessful();
  }

  public function testList(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/discipline/list/book/2');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/vampire/wiki/discipline/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/vampire/wiki/discipline/1');
    self::assertResponseIsSuccessful();
  }

  public function testNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/discipline/new');
    self::assertResponseIsSuccessful();
  }

  public function testSorceryNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/sorcery/new');
    self::assertResponseIsSuccessful();
  }

  public function testThaumaturgyNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/thaumaturgy/new');
    self::assertResponseIsSuccessful();
  }

  public function testCoilNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/coil/new');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/discipline/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testDelete(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/discipline/1/delete');
    self::assertResponseRedirects();
  }

  public function testPowerNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/discipline/1/power/add');
    self::assertResponseIsSuccessful();
  }

  public function testPowerEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/discipline/power/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testPowerDelete(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/discipline/power/1/delete');
    self::assertResponseRedirects();
  }
}
