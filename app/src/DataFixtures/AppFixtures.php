<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // erstellt immer einen Test-User
        UserFactory::createOne(['email' => 'email', 'password' => 'password', 'is_deleted' => false]);
        // erstellt 10 Fake-User
        UserFactory::createMany(10);
    }
}
