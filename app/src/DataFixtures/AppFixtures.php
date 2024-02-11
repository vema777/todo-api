<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Organization;
use App\Entity\User;
use App\Factory\ApiTokenFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        ApiTokenFactory::createMany(10, function() {
            return [
                'ownedBy' => UserFactory::new(),
            ];
        });

        // erstellt einen Eigentümer-Nutzer
        $ownerUser = new User();
        $ownerUser->setEmail('owner@example.com');
        $ownerUser->setPassword($this->passwordHasher->hashPassword($ownerUser, 'password'));
        $ownerUser->setFirstName('John');
        $ownerUser->setLastName('Smith');
        $ownerUser->setRoles(['ROLE_ORGANIZATION_OWNER']);
        $manager->persist($ownerUser);

        $apiTokenOwner = new ApiToken();
        $apiTokenOwner->setOwnedBy($ownerUser);
        $manager->persist($apiTokenOwner);

        // erstellt einen Admin-Nutzer
        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, 'password'));
        $adminUser->setFirstName('Donald');
        $adminUser->setLastName('Scrooge');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);

        $apiTokenAdmin = new ApiToken();
        $apiTokenAdmin->setOwnedBy($adminUser);
        $manager->persist($apiTokenAdmin);

        // erstellt eine Organisation
        $organization = new Organization();
        $organization->setName('Oxygen GmbH');

        $organization->addUser($ownerUser);
        $organization->setOwner($ownerUser);
        $organization->addUser($adminUser);

        $manager->persist($organization);

        $manager->flush();
    }
}
