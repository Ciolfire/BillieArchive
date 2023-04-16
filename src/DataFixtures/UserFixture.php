<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("test");
        $user->setEmail("test@test.com");
        $user->setPassword("test");
        $user->setRoles(['ROLE_GM']);
        $manager->persist($user);

        $manager->flush();
    }
}
