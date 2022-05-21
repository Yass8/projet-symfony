<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        //session_start()
        $session = $request->getSession();
        if ($session->has('nbVisite')){ //Verifier si la var nbVisite est sur la session

            $nbreVisite = $session->get('nbVisite') + 1; // Recupere la var et l'implmenter
        }else{
            $nbreVisite = 1;
        }
        $nbreVisite = $session->set('nbVisite', $nbreVisite);//Modifie la var
        return $this->render('session/index.html.twig');
    }
}
