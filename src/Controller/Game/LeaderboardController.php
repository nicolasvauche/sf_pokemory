<?php

namespace App\Controller\Game;

use App\Service\MemoryGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LeaderboardController extends AbstractController
{
    #[Route('/classement', name: 'app_leaderboard')]
    public function index(MemoryGameService $memoryGameService): Response
    {
        $leaderboards = $memoryGameService->getLeaderboards();

        return $this->render('game/leaderboard/index.html.twig', [
            'leaderboards' => $leaderboards,
        ]);
    }
}
