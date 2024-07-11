<?php

namespace App\Service;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;

class MemoryGameService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPokemonsForMemoryGame(int $limit): array
    {
        if($limit % 2 !== 0) {
            throw new \InvalidArgumentException('Le nombre total de Pokémon doit être pair.');
        }

        $numberOfPairs = $limit / 2;
        $pokemons = $this->fetchBasePokemonsFromDatabase($numberOfPairs);
        $pairs = $this->createPairs($pokemons);
        $shuffledPairs = $this->shuffleArray($pairs);

        return $shuffledPairs;
    }

    private function fetchBasePokemonsFromDatabase(int $number): array
    {
        $pokemonRepository = $this->entityManager->getRepository(Pokemon::class);
        $pokemons = $pokemonRepository->findBy([], null, $number);

        return $pokemons;
    }

    private function createPairs(array $pokemons): array
    {
        $pairs = [];
        foreach($pokemons as $pokemon) {
            $pairs[] = [
                'name' => $pokemon->getName(),
                'image' => $pokemon->getImage(),
            ];
            $pairs[] = [
                'name' => $pokemon->getName(),
                'image' => $pokemon->getImage(),
            ];
        }

        return $pairs;
    }

    private function shuffleArray(array $array): array
    {
        $shuffledArray = $array;
        shuffle($shuffledArray);

        return $shuffledArray;
    }
}
