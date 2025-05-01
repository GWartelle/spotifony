<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixture extends Fixture
{
    private $passwordHasherFactory;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasherFactory = $passwordHasherFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $manager->persist($user);

        $manager->flush();
    }
}
