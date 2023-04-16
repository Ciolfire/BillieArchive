<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
  /**
   * @dataProvider urlProvider
   */
  public function testPageIsSuccessful($url)
  {
    $client = self::createClient();
    
    $userRepository = static::getContainer()->get(UserRepository::class);
    // retrieve the test user
    $testUser = $userRepository->findOneByUsername('test');

    // simulate $testUser being logged in
    $client->loginUser($testUser);


    $client->request('GET', $url);

    $this->assertResponseIsSuccessful();
  }

  public function urlProvider()
  {
    yield ['/en/'];



    // VAMPIRE
    //// CLAN
    yield ['/en/vampire/clans'];
    // yield ['/en/vampire/clan/fixture-clan-1'];
    yield ['/en/vampire/clan/0/new'];
    yield ['/en/vampire/clan/1/new'];
    // yield ['/en/vampire/clan/fixture-clan-1/edit'];
    // Redirect, how ? yield ['/en/vampire/bloodlines'];
    // yield ['/en/vampire/fixture-character-1/bloodline/join'];
    // yield ['/en/vampire/clan/book/1'];
    //// DEVOTION
    yield ['/en/vampire/devotions'];


    // yield ['/post/fixture-post-1'];
    // yield ['/blog/category/fixture-category'];
    // yield ['/archives'];
    // ...
  }
}
