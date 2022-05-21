<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/{nb<\d+>?5}', name: 'app_tab')]
    public function index($nb): Response
    {
        $notes = [];
        for ($i = 0; $i<$nb ; $i++){
            $notes[] = rand(0,20);
        }
        return $this->render('tab/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    #[Route('/tab/users', name: 'tab.user')]
    public function users(): Response
    {
        $users = [
            ['firstname'=>'yassir', 'lastname'=>'ali', 'age'=>22],
            ['firstname'=>'moussa', 'lastname'=>'moussa', 'age'=>50],
            ['firstname'=>'yazid', 'lastname'=>'ali', 'age'=>15],
            ['firstname'=>'ali', 'lastname'=>'ibrahim', 'age'=>10],

        ];
        return $this->render('tab/users.html.twig', [
            'users' => $users,
        ]);
    }
}
