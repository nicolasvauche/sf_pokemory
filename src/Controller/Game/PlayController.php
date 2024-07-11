<?php

namespace App\Controller\Game;

use App\Repository\GameRepository;
use App\Service\MemoryGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayController extends AbstractController
{
    #[Route('/jouer/{mode}', name: 'app_game_play')]
    public function index(MemoryGameService $memoryGameService, string $mode = null): Response
    {
        if(!$mode) {
            $this->addFlash('warning', 'Choisissez un mode de jeu');

            return $this->redirectToRoute('app_game_home');
        }

        if($this->isGranted("ROLE_ADMIN")) {
            $this->addFlash('warning', "Vous ne pouvez pas jouer en tant qu'administrateur !");

            return $this->redirectToRoute('app_game_home');
        }

        switch($mode) {
            case 'debutant':
                $modeName = 'Débutant';
                $nbCards = 4;
                break;
            case 'avance':
                $modeName = 'Avancé';
                $nbCards = 16;
                break;
            case 'expert':
                $modeName = 'Expert';
                $nbCards = 36;
                break;
            default:
                $modeName = '';
                $nbCards = 0;
                break;
        }

        $game = $memoryGameService->startNewGame($mode);

        $pokemons = $memoryGameService->getPokemonsForMemoryGame($nbCards);

        if(sizeof($pokemons) === 0) {
            $this->addFlash('warning', "Aucun Pokémon disponible ! Demandez à l'administrateur de les charger");

            return $this->redirectToRoute('app_game_home');
        }

        return $this->render('game/play/index.html.twig', [
            'mode' => $mode,
            'modeName' => $modeName,
            'pokemons' => $pokemons,
            'gameId' => $game->getId(),
        ]);
    }

    #[Route('/complete-game/{gameId}/{tries}', name: 'app_game_complete', methods: ['POST'])]
    public function completeGame(GameRepository $gameRepository, MemoryGameService $memoryGameService, int $gameId, int $tries): JsonResponse
    {
        $game = $gameRepository->find($gameId);

        if($game) {
            $memoryGameService->completeGame($game, $tries);

            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Game not found'], 404);
    }
}
