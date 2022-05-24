<?php

namespace App\Controller;

//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{

    #[Route('/order/{var}', name: 'order')]
    public function testOrderRoute($var){
        return new Response($var);
    }

    #[Route('/first', name: 'app_first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'nom' => 'text',
            'cap' => 'capitalisé',
            'path' => ''
        ]);
    }

    #[Route('/hello/{nom}/{prenom}', name: 'helllo')]
    public function hello($nom, $prenom): Response
    {
        return $this->render('first/hello.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom
        ]);
    }

    #[Route('/filtres', name: 'app_firstd')]
    public function hellos(): Response
    {
        return $this->forward('App\Controller\FirstController::index');
    }

    //Requirements = contraintes
    // requirements syntaxe 1
    #[Route('multi/{entier1}/{entier2}', requirements: ['entier1'=>'\d+','entier2'=>'\d+'],name: 'multiplication')]
    public function multiplication($entier1,$entier2): Response
    {
        $res = $entier1*$entier2;
        return new Response("<h1>$res</h1>");
    }

    // requirements syntaxe 2
    #[Route('addition/{entier1<\d+>}/{entier2<\d+>}', name: 'addition')]
    public function addition($entier1,$entier2): Response
    {
        $res = $entier1+$entier2;
        return new Response("<h1>$res</h1>");
    }
}
