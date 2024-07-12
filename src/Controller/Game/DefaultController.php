<?php

namespace App\Controller\Game;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/nouvelle-partie', name: 'app_game_home')]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('game/default/index.html.twig', [
            'pokemons' => $pokemonRepository->findAll(),
        ]);
    }
}
