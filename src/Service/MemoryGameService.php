<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MemoryGameService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getPokemonsForMemoryGame(int $limit): array
    {
        $pokemonRepository = $this->entityManager->getRepository(Pokemon::class);
        $pokemons = $pokemonRepository->findBy([], null, $limit / 2);

        $pairs = [];
        foreach($pokemons as $pokemon) {
            $pairs[] = $pokemon;
            $pairs[] = $pokemon;
        }

        shuffle($pairs);

        return $pairs;
    }

    public function startNewGame(string $mode): Game
    {
        $player = $this->security->getUser();

        $existingGame = $this->entityManager->getRepository(Game::class)->findOneBy([
            'player' => $player,
            'completed' => false,
        ]);

        if($existingGame) {
            $this->entityManager->remove($existingGame);
            $this->entityManager->flush();
        }

        $game = new Game();
        $game->setPlayer($player);
        $game->setMode($mode);
        $game->setTries(0);
        $game->setCompleted(false);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game;
    }

    public function completeGame(Game $game, int $tries): void
    {
        $game->setCompleted(true);
        $game->setTries($tries);
        $game->setCompletedAt(new \DateTimeImmutable());

        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }
}
