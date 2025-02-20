<?php

namespace App\Tests\Controller;

use App\Controller\Vampire\VampireController;
use App\Entity\User;
use App\Entity\Vampire;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(VampireController::class)]
#[CoversClass(Vampire::class)]
final class VampireControllerTest extends WebTestCase
{
  public function getUser(): User
  {
    $userRepository = static::getContainer()->get(UserRepository::class);
    return $userRepository->findOneByUsername('Ciol');
  }

  public function testEmbrace(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/vampire/287/embrace');

    self::assertResponseIsSuccessful();
  }
}
