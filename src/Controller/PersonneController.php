<?php

namespace App\Controller;

use App\Entity\Personne;
//use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }

    #[Route('/all/{page?1}/{nbre?12}', name: 'personne.all')]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findBy([],[],$nbre,($page-1)*$nbre);

        $nbPersonne = $repository->count([]);

        //ceil arrondis la partie sup
        $nbPage = ceil($nbPersonne/$nbre);

        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbPage' => $nbPage,
            'pageActuelle' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response
    {

        //ManagerRegistry $doctrine, $id
        //$repository = $doctrine->getRepository(Personne::class);
        //$personne = $repository->find($id);

        /*
         * Ici j'ai utilisé param convertor
         * Je passe un objet de personne sur la parametre
         * Il va se charger de chercher le repository
         * */
        if(!$personne){
            $this->addFlash('error',"La personne n'existe pas.");
            return $this->redirectToRoute('personne');
        }
        return $this->render('personne/details.html.twig', [
            'personne' => $personne,
        ]);
    }

    #[Route('/add', name: 'add_personne')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setFirstname('Zainaba');
        $personne->setLastname('Ibrahim');
        $personne->setAge(25);

        //ajouter l'opération d'insertion
        $entityManager->persist($personne);

        // execution
        $entityManager->flush();

        return $this->render('personne/details.html.twig', [
            'personne' => $personne
        ]);
    }

    #[Route('/delete/{id}', name: 'person.delete')]
    public  function  deletePersonne(ManagerRegistry $doctrine, Personne $personne= null): RedirectResponse {

        if($personne){
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();

            $this->addFlash('success', "La personne a été supprimée avec succès");
        }else{
            $this->addFlash('error',"Personne innexistante");
        }
        return $this->redirectToRoute('personne.all');
    }

    #[Route('/update/{id}/{firstname}/{lastname}/{age}', name: 'person.update')]
    public  function  updatePersonne(ManagerRegistry $doctrine, Personne $personne = null, $firstname, $lastname, $age): RedirectResponse {

        if($personne){
            $manager = $doctrine->getManager();

            $personne->setFirstname($firstname);
            $personne->setLastname($lastname);
            $personne->setAge($age);

            $manager->persist($personne);
            $manager->flush();

            $this->addFlash('success', "La personne a été mise à jour avec succès");
        }else{
            $this->addFlash('error',"Personne innexistante");
        }
        return $this->redirectToRoute('personne.all');
    }

}
