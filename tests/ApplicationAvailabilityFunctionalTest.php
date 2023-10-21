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
    // MAIN
    yield ['/en/'];
    // yield ['/en/login']; // Redirect
    // yield ['/logout']; // Redirect
    yield ['/en/users'];
    yield ['/en/register'];
    // yield ['/en/verify/email'];
    // yield ["/en/user/switch/fixture-user-1/ROLE_GM"];
    // yield ["/en/user/fixture-user-1/activate"];

    // CHARACTER
    yield ['/en/character'];
    yield ['/en/character/premade'];
    yield ['/en/character/npc'];
    yield ['/en/character/new/0/0'];
    yield ['/en/character/new/template'];
    // yield ['/en/character/fixture-character-1'];
    // yield ['/en/character/fixture-character-1/edit'];
    // yield ['/en/character/fixture-character-1/delete'];
    // yield ['/en/character/fixture-character-1/duplicate'];
    // yield ['/en/character/fixture-character-1/background'];
    // yield ['/en/character/fixture-character-1/note/new'];
    // yield ['/en/character/fixture-character-1/note/edit'];
    // yield ['/en/character/'];

    // CHRONICLE
    yield ['/en/chronicle/new'];
    // yield ['/en/chronicle/fixture-chronicle-1'];
    // yield ['/en/chronicle/fixture-chronicle-1/homebrew'];
    // yield ['/en/chronicle/fixture-chronicle-1/party'];
    // yield ['/en/chronicle/fixture-chronicle-1/npc'];
    // yield ['/en/chronicle/fixture-chronicle-1/npc/add'];
    // yield ['/en/chronicle/fixture-chronicle-1/party/add'];
    // yield ['/en/chronicle/fixture-chronicle-1/party/remove/player'];
    // yield ['/en/chronicle/fixture-chronicle-1/note/category/new'];
    // yield ['/en/chronicle/fixture-chronicle-1/note/category/fixture-category-1/edit'];
    // yield ['/en/chronicle/fixture-chronicle-1/note/category/fixture-category-1/delete'];

    // DERANGEMENT
    // yield ['/en/derangements'];
    // yield ['/en/derangement/list/book/1'];
    yield ['/en/derangement/new'];
    // yield ['/en/derangement/fixture-derangement-1'];
    // yield ['/en/derangement/fixture-derangement-1/edit'];
    // yield ['/en/derangement/fixture-derangement-1/translate/fr'];
    // yield ['/en/derangement/fixture-derangement-1/delete'];

    // MERIT
    yield ['/en/merit/new'];
    // yield ['/en/merit/list/book/1'];
    // yield ['/en/merit/fixture-merit-1'];
    // yield ['/en/merit/fixture-merit-1/edit'];
    // yield ['/en/merit/fixture-merit-1/delete'];

    // NOTE
    // yield ['/en/chronicle/fixture-chronicle-1/note/new'];
    // yield ['/en/chronicle/fixture-chronicle-1/note/edit'];
    // yield ['/en/chronicle/fixture-chronicle-1/note/delete'];
    // yield ['/en/chronicle/note/fixture-note-1'];
    // yield ['/en/chronicle/fixture-chronicle-1/notes/fixture-category-1'];
    // yield ['/en/chronicle/fixture-chronicle-1/notes/search'];

    // ROLL
    yield ['/en/roll/new'];
    // yield ['/en/roll/fixture-roll-1'];
    // yield ['/en/roll/fixture-roll-1/edit'];
    // yield ['/en/roll/fixture-roll-1/translate/fr'];
    // yield ['/en/roll/fixture-roll-1/delete'];
    yield ['/en/roll'];
    yield ['/en/roll/human'];

    // RULE
    yield ['/en/rule/new'];
    // yield ['/en/rule/fixture-rule-1'];
    // yield ['/en/rule/fixture-rule-1/edit'];
    // yield ['/en/rule/fixture-rule-1/delete'];
    // yield ['/en/rule/fixture-rule-1/'];

    // WIKI
    yield ['/en/wiki'];
    yield ['/en/wiki/attributes'];
    // yield ['/en/wiki/attributes/fixture-attribute-1/edit'];
    yield ['/en/wiki/skills'];
    // yield ['/en/wiki/skills/fixture-skill-1/edit'];


    // AJAX
    // yield ['/en/character/fixture-character-1/wounds/update'];
    // yield ['/en/character/fixture-character-1/trait/update'];
    // yield ['/en/character/fixture-character-1/experience/update'];
    // yield ['/en/character/fixture-character-1/avatar/update'];

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
