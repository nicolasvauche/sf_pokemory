<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PokemonController extends AbstractController
{
    #[Route('/administration/pokemon', name: 'app_admin_pokemon')]
    public function index(): Response
    {
        return $this->render('admin/pokemon/index.html.twig');
    }
}
