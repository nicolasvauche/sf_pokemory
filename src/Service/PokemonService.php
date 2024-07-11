<?php

namespace App\Service;

use Doctrine\DBAL\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pokemon;

class PokemonService
{
    private Client $client;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(Client $client, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function fetchAndStorePokemons(int $limit = 36): void
    {
        $this->truncatePokemonTable();

        $url = 'https://pokeapi.co/api/v2/pokemon?limit=100';
        $basePokemons = [];

        try {
            $offset = 0;
            while(count($basePokemons) < $limit) {
                $response = $this->client->request('GET', $url . '&offset=' . $offset);
                $data = json_decode($response->getBody()->getContents(), true);

                foreach($data['results'] as $pokemonData) {
                    $pokemonDetails = $this->fetchPokemonDetails($pokemonData['url']);
                    if($pokemonDetails && $this->isBasePokemon($pokemonDetails['species']['url'])) {
                        $basePokemons[] = $pokemonDetails;
                        if(count($basePokemons) >= $limit) {
                            break;
                        }
                    }
                }
                $offset += 100;
            }

            foreach($basePokemons as $pokemonDetails) {
                $pokemon = new Pokemon();
                $pokemon->setName($this->getPokemonNameInFrench($pokemonDetails['species']['url']));
                $pokemon->setImage($pokemonDetails['sprites']['other']['official-artwork']['front_default']);
                $this->entityManager->persist($pokemon);
            }

            $this->entityManager->flush();

        } catch(\Exception $e) {
            $this->logger->error('Error fetching Pokémon data: ' . $e->getMessage());
        } catch(GuzzleException $e) {
            dd($e->getMessage());
        }
    }

    private function fetchPokemonDetails(string $url)
    {
        try {
            $response = $this->client->request('GET', $url);

            return json_decode($response->getBody()->getContents(), true);
        } catch(\Exception $e) {
            $this->logger->error('Error fetching Pokémon details: ' . $e->getMessage());

            return null;
        } catch(GuzzleException $e) {
            dd($e->getMessage());
        }
    }

    private function isBasePokemon(string $speciesUrl): bool
    {
        try {
            $response = $this->client->request('GET', $speciesUrl);
            $speciesDetails = json_decode($response->getBody()->getContents(), true);

            return empty($speciesDetails['evolves_from_species']);
        } catch(\Exception $e) {
            $this->logger->error('Error fetching Pokémon species details: ' . $e->getMessage());

            return false;
        } catch(GuzzleException $e) {
            dd($e->getMessage());
        }
    }

    private function getPokemonNameInFrench(string $speciesUrl): string
    {
        try {
            $response = $this->client->request('GET', $speciesUrl);
            $speciesDetails = json_decode($response->getBody()->getContents(), true);
            foreach($speciesDetails['names'] as $name) {
                if($name['language']['name'] === 'fr') {
                    return $name['name'];
                }
            }

            return $speciesDetails['name'];
        } catch(\Exception $e) {
            $this->logger->error('Error fetching Pokémon species details: ' . $e->getMessage());

            return 'Unknown';
        } catch(GuzzleException $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function truncatePokemonTable(): void
    {
        $connection = $this->entityManager->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        $truncateSql = $databasePlatform->getTruncateTableSQL('pokemon');
        $connection->executeStatement($truncateSql);
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
