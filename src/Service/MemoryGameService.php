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
        $allPokemons = $pokemonRepository->findAll();

        shuffle($allPokemons);
        $pokemons = array_slice($allPokemons, 0, $limit / 2);

        $pairs = [];
        foreach($pokemons as $pokemon) {
            $pairs[] = $pokemon;
            $pairs[] = clone $pokemon;
        }

        shuffle($pairs);

        return $pairs;
    }

    public function startNewGame(string $mode): Game
    {
        $player = $this->security->getUser();

        // Check if there is an ongoing game for the player
        $existingGame = $this->entityManager->getRepository(Game::class)->findOneBy([
            'player' => $player,
            'completed' => false,
        ]);

        // If an existing game is found, remove it
        if($existingGame) {
            $this->entityManager->remove($existingGame);
            $this->entityManager->flush();
        }

        // Create a new game
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

    public function getLeaderboards(): array
    {
        $modes = ['debutant', 'avance', 'expert'];
        $leaderboards = [];

        foreach($modes as $mode) {
            $leaderboards[$mode] = $this->entityManager->getRepository(Game::class)->createQueryBuilder('g')
                ->where('g.mode = :mode')
                ->andWhere('g.completed = true')
                ->orderBy('g.tries', 'ASC')
                ->addOrderBy('g.completedAt - g.createdAt', 'ASC')
                ->setParameter('mode', $mode)
                ->getQuery()
                ->getResult();
        }

        return $leaderboards;
    }
}
