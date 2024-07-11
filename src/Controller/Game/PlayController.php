<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayController extends AbstractController
{
    #[Route('/jouer/{mode}', name: 'app_game_play')]
    public function index(string $mode = null): Response
    {
        if(!$mode) {
            $this->addFlash('warning', 'Choisissez un mode de jeu');

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

        return $this->render('game/play/index.html.twig', [
            'mode' => $mode,
            'modeName' => $modeName,
            'nbCards' => $nbCards,
        ]);
    }
}
