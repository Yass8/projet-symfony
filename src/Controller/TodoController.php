<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Préfixer les routes
#[Route("/todo")]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        //Afficher tableau de todo

        // Si j'ai mon tableau de todo dans ma session je ne fait que l'afficher

        // Sinon je l'initialise puis l'afficher
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter un clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            ];

            $session->set('todos',$todos);
            $this->addFlash('info',"La liste des todos viens d'être initialisée");
        }
        return $this->render('todo/index.html.twig');
    }

    // Valeur par défaut syntaxe 1
    #[Route('/add/{indice}/{element}', name: 'todo.add', defaults: ['indice'=>'AZ','element'=>'sf6AZ'])]
    public function addTodo(Request $request, $indice, $element): RedirectResponse {
        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')){
            //si oui
            // vérifie si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if(isset($todos[$indice])){
                // si oui afficher erreur
                $this->addFlash('error',"Le todo d'id $indice existe déjà dans la liste");
            }else{
                // si non on l'ajoute et on affiche un message de succes
                $todos[$indice] = $element;
                $this->addFlash('success',"Le todo d'id $indice a été ajouté avec succès");
                $session->set('todos',$todos);
            }
        }else{
            // Si non
            // Afficher une erreur et on va rediriger vers le controller index
            $this->addFlash('error',"La liste des todos n'est pas encore initialisée");
        }

        return $this->redirectToRoute('app_todo');

    }

    // Valeur par défaut syntaxe 2
    #[Route('/update/{indice}/{element?contenu}', name: 'todo.update')]
    public function updateTodo(Request $request, $indice, $element): RedirectResponse {
        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')){
            //si oui
            // vérifie si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if(!isset($todos[$indice])){
                // si oui afficher erreur
                $this->addFlash('error',"Le todo d'id $indice n'existe pas dans la liste");
            }else{
                // si non on l'ajoute et on affiche un message de succes
                $todos[$indice] = $element;
                $session->set('todos',$todos);
                $this->addFlash('success',"Le todo d'id $indice a été modifié avec succès");

            }
        }else{
            // Si non
            // Afficher une erreur et on va rediriger vers le controller index
            $this->addFlash('error',"La liste des todos n'est pas encore initialisée");
        }

        return $this->redirectToRoute('app_todo');

    }

    #[Route('/delete/{indice}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $indice): RedirectResponse {
        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')){
            //si oui
            // vérifie si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if(!isset($todos[$indice])){
                // si oui afficher erreur
                $this->addFlash('error',"Le todo d'id $indice n'existe pas dans la liste");
            }else{
                // si non on l'ajoute et on affiche un message de succes
                //suppression de l'element
                unset($todos[$indice]);
                $session->set('todos',$todos);
                $this->addFlash('success',"Le todo d'id $indice a été supprimé avec succès");

            }
        }else{
            // Si non
            // Afficher une erreur et on va rediriger vers le controller index
            $this->addFlash('error',"La liste des todos n'est pas encore initialisée");
        }

        return $this->redirectToRoute('app_todo');

    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse {
        $session = $request->getSession();

        $session->remove('todos');

        return $this->redirectToRoute('app_todo');

    }
}
