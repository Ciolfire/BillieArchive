<?php

namespace App\Tests\Controller;

use App\Controller\Mage\ArcanumController;
use App\Entity\Arcanum;
use App\Entity\MageSpell;
use App\Entity\MagicalPractice;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ArcanumController::class)]
#[CoversClass(Arcanum::class)]
#[CoversClass(MageSpell::class)]
#[CoversClass(MagicalPractice::class)]
final class ArcanumControllerTest extends WebTestCase
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
    $client->request('GET', '/en/mage/wiki/arcana');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/arcanum/1');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/mage/arcanum/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testSpellIndex(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/spells');
    self::assertResponseIsSuccessful();
  }

  public function testSpellList(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/spells/list/book/1');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/mage/wiki/spells/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testSpellShow(): void
  {
    $client = static::createClient();
    $client->request('GET', '/en/mage/wiki/spell/1');
    self::assertResponseIsSuccessful();
  }

  public function testSpellNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/mage/spell/new');
    self::assertResponseIsSuccessful();
  }

  public function testSpellEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/mage/spell/1/edit');
    self::assertResponseIsSuccessful();
  }
}
