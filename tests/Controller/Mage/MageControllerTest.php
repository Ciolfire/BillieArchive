<?php

namespace App\Tests\Controller\Mage;

use App\Controller\Mage\MageController;
use App\Entity\Mage;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MageController::class)]
#[CoversClass(Mage::class)]
final class MageControllerTest extends WebTestCase
{
  public function getUser(): User
  {
    $userRepository = static::getContainer()->get(UserRepository::class);
    return $userRepository->findOneByUsername('Ciol');
  }

  public function testAwakening(): void
  {
    $client = static::createClient();
    $client->loginUser($this->getUser());
    $client->request('GET', '/en/mage/29/awakening');

    self::assertResponseIsSuccessful();
  }
}
