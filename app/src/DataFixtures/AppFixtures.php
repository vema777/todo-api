<?php

namespace App\DataFixtures;

use App\Factory\ApiTokenFactory;
use App\Factory\OrganizationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // erstellt immer einen Eigentümer-User
        /*$ownerUser = UserFactory::createOne([
            'email' => 'organization_owner',
            'password' => 'password',
            'is_deleted' => false,
            'roles' => ['ROLE_ORGANIZATION_OWNER'],

        ]);
        ApiTokenFactory::createOne(['ownedBy' => $ownerUser]);*/
        // erstellt immer einen Admin User
        /*$adminUser = UserFactory::createOne([
            'email' => 'admin',
            'password' => 'password',
            'is_deleted' => false,
            'roles' => ['ROLE_ADMIN'],
        ]);
        ApiTokenFactory::createOne(['ownedBy' => $adminUser]);*/
        // erstellt einen normalen User
        /*$normalUser = UserFactory::createOne([
            'email' => 'user',
            'password' => 'password',
            'is_deleted' => false,
            'roles' => ['ROLE_USER'],
        ]);
        ApiTokenFactory::createOne(['ownedBy' => $normalUser]);

        ApiTokenFactory::createMany(10, function() {
            return [
                'ownedBy' => UserFactory::new(),
            ];
        });*/

        OrganizationFactory::createMany(
            3,
            ['users' => UserFactory::new()->many(3, 6)]
        );


    }
}
