<?php

namespace App\Controller\Admin;

use App\Repository\PokemonRepository;
use App\Service\PokemonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PokemonController extends AbstractController
{
    #[Route('/administration/pokemon', name: 'app_admin_pokemon')]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('admin/pokemon/index.html.twig', [
            'pokemons' => $pokemonRepository->findAll(),
        ]);
    }

    #[Route('/administration/pokemon/charger', name: 'app_admin_pokemon_fetch')]
    public function fetch(PokemonService $pokemonService): Response
    {
        $pokemonService->fetchAndStorePokemons();

        $this->addFlash('success', 'Les Pokémon ont été chargés !');

        return $this->redirectToRoute('app_admin_pokemon');
    }
}
