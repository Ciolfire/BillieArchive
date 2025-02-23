<?php

namespace App\Tests\Controller;

use App\Controller\CharacterController;
use App\Entity\User;
use App\Entity\Character;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CharacterController::class)]
#[CoversClass(Character::class)]
class CharacterControllerTest extends WebTestCase
{
  public function getUser(): User
  {
    $userRepository = static::getContainer()->get(UserRepository::class);
    return $userRepository->findOneByUsername('Ciol');
  }

  public function testIndex(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/characters');
    self::assertResponseIsSuccessful();
  }

  public function testPremadeIndex(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/characters/premades');
    self::assertResponseIsSuccessful();
  }

  public function testNpcIndex(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/characters/npc');
    self::assertResponseIsSuccessful();
  }

  public function testList(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/characters/list/book/2');
    self::assertResponseIsSuccessful();
    
    $client->request('GET', '/en/characters/list/chronicle/1');
    self::assertResponseIsSuccessful();
  }

  public function testShow(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1');
    self::assertResponseIsSuccessful();
  }

  public function testNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/new');
    self::assertResponseIsSuccessful();
  }

  public function testPremadeNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/premade/new');
    self::assertResponseIsSuccessful();
  }

  public function testEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/edit');
    self::assertResponseIsSuccessful();
  }

  public function testDelete(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/delete');
    self::assertResponseRedirects();
  }

  public function testPeek(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/peek');
    self::assertResponseRedirects();
  }

  public function testPeekAs(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/peek/1');
    self::assertResponseRedirects();
  }

  // public function testFetchPeek(): void
  // {
  //   $client = static::createClient();
  //   $client->loginUser($this->getUser());
  //   $client->request('GET', '/en/character/1/1/a_peek');
  //   self::assertResponseRedirects();
  // }

  public function testDuplicate(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/duplicate');
    self::assertResponseIsSuccessful();
  }

  public function testApplyLesser(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/lesser/add');
    self::assertResponseIsSuccessful();
  }

  public function testRemoveLesser(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/lesser/remove');
    self::assertResponseRedirects();
  }

  public function testAccessAdd(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/access/add');
    self::assertResponseIsSuccessful();
  }

  public function testAccessEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/access/168');
    self::assertResponseIsSuccessful();
  }

  public function testBasicInfosEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/infos/basic/background');
    self::assertResponseIsSuccessful();

    $client->request('GET', '/en/character/1/infos/basic/description');
    self::assertResponseIsSuccessful();
  }

  public function testInfosEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/infos/basic/background');
    self::assertResponseIsSuccessful();

    $client->request('GET', '/en/character/1/infos/edit');
    self::assertResponseIsSuccessful();
  }

  public function testItemList(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/item/list');
    self::assertResponseIsSuccessful();
  }

  public function testItemContainerAdd(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/item/container/add');
    self::assertResponseIsSuccessful();
  }

  public function testItemNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/item/new');
    self::assertResponseIsSuccessful();
  }

  public function testItemAdd(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/item/26/add');
    self::assertResponseIsSuccessful();
  }

  public function testNoteNew(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/note/new');
    self::assertResponseIsSuccessful();
  }

  public function testNoteEdit(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/character/1/notes/51/edit');
    self::assertResponseIsSuccessful();
  }
}
