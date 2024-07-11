<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setEmail('admin@pokemory.com')
            ->setPassword($this->passwordHasher->hashPassword($admin, 'admin'))
            ->setRoles(["ROLE_ADMIN"])
            ->setPseudo('Administrateur');
        $manager->persist($admin);

        $player = new Player();
        $player->setEmail('player@pokemory.com')
            ->setPassword($this->passwordHasher->hashPassword($player, 'player'))
            ->setPseudo('nicolas')
            ->setActive(true);
        $manager->persist($player);

        $player = new Player();
        $player->setEmail('player2@pokemory.com')
            ->setPassword($this->passwordHasher->hashPassword($player, 'player'))
            ->setPseudo('bob')
            ->setActive(true);
        $manager->persist($player);

        $manager->flush();
    }
}
