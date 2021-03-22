<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Repository\StageRepository;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EntrepriseType;

class ProjetStageController extends AbstractController
{
    /**
     * @Route("/", name="projet_stage_accueil")
     */
    public function index(StageRepository $repositoryStage): Response
    {
      //récupérer les stages enregistrés en BD
      $stages = $repositoryStage->findAll();

      //Envoyer les stages récupérer à la vue chargé de les afficher
      return  $this ->render('projet_stage/index.html.twig', ['stages'=>$stages]);
    }

    /**
     * @Route("/entreprise/ajouter", name="projet_stage_ajoutEntreprise")
     */
    public function ajouterEntreprise(Request $request, EntityManagerInterface $manager): Response
    {
      //Création d'une entreprise vierge qui sera remplie par le formulaire
      $entreprise = new Entreprise();

      //Création du formulaire permettant de saisir une entreprises
      $formulaireEntreprise = $this->createForm(EntrepriseType::class, $entreprise);

      /*On demande au formulaire d'analyser la derniere requête Http.
      Si le tableau POST contenu dans cette requête contient des variables
      nom, adresse, etc, alors la méthode handleRequest() récupère les valeurs
      et les affecte à l'objet $entreprise */
      $formulaireEntreprise->handleRequest($request);

      if ($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid())
      {
        //Enregistrer l'entreprise en base de donnéelse
        $manager->persist($entreprise);
        $manager->flush();

        //Rediriger l'utilisaateur vers la page d'accueil
        return $this->redirectToRoute('projet_stage_accueil');

      }

      //afficher la page présentant le formulaire d'ajout d'une entreprise
      return  $this -> render('projet_stage/ajoutModifEntreprise.html.twig',
      ['vueFormulaire'=>$formulaireEntreprise->createView(), 'action' => "ajouter"]);
    }

    /**
     * @Route("/entreprise/modifier/{id}", name="projet_stage_modifierEntreprise")
     */
    public function modifierEntreprise(Request $request, EntityManagerInterface $manager, Entreprise $entreprise): Response
    {
      //Création du formulaire permettant de modifier une entreprise
      $formulaireEntreprise = $this->createForm(EntrepriseType::class, $entreprise);

      /*On demande au formulaire d'analyser la derniere requête Http.
      Si le tableau POST contenu dans cette requête contient des variables
      nom, adresse, etc, alors la méthode handleRequest() récupère les valeurs
      et les affecte à l'objet $entreprise */
      $formulaireEntreprise->handleRequest($request);

      if ($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid())
      {
        //Enregistrer l'entreprise en base de donnéelse
        $manager->persist($entreprise);
        $manager->flush();

        //Rediriger l'utilisaateur vers la page d'accueil
        return $this->redirectToRoute('projet_stage_accueil');

      }

      //afficher la page présentant le formulaire d'ajout d'une entreprise
      return  $this -> render('projet_stage/ajoutModifEntreprise.html.twig',
      ['vueFormulaire'=>$formulaireEntreprise->createView(), 'action' => "modifier"]);
    }

    /**
     * @Route("/stages/{id}", name="projet_stage_Stages")
     */
    public function stages(Stage $stage): Response
    {
      return  $this ->render('projet_stage/stages.html.twig',
      ['stage'=> $stage]);
    }

    /**
     * @Route("/entreprise/{nom}", name="projet_stage_enreprise")
     */
    public function entreprise($nom): Response
    {
      //récupérer le repository de l'entité Stage
      $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

      $stages = $repositoryStage->findByNomEntreprise($nom);

      return  $this ->render('projet_stage/entreprise.html.twig',
      ['stages'=> $stages,'entreprise'=> $nom]);
    }

    /**
     * @Route("/formation/{intitule}", name="projet_stage_formation")
     */
    public function formations($intitule): Response
    {
      //récupérer le repository de l'entité Stage
      $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

      //récupérer les stages de la formation qui a pour id celui passé en paramètre qui est enregistré en BD
      $stages = $repositoryStage->findByNomFormation($intitule);

      return  $this ->render('projet_stage/formation.html.twig',
      ['stages'=>  $stages, 'intitule'=> $intitule]);
    }
    /**
     * @Route("/filtreFormation", name="projet_stage_filtre_formation")
     */
    public function filtreFormations(): Response
    {
      // Récupérer le repository de l'entité Formation
      $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);

      // Récupérer les stages par formations enregistrées en BD
      $formations=$repositoryFormation->findAll();

      return  $this ->render('projet_stage/filtreFormation.html.twig',
      ['formations' => $formations]);
    }

    /**
     * @Route("/filtreEntreprise", name="projet_stage_filtre_entreprise")
     */
    public function filtreEntreprise(): Response
    {
      // Récupérer le repository de l'entité Entreprise
      $repositoryEntreprise = $this->getDoctrine()->getRepository(Entreprise::class);

      // Récupérer les stages par entreprise enregistrées en BD
      $entreprises = $repositoryEntreprise->findAll();

      return  $this ->render('projet_stage/filtreEntreprise.html.twig',
      ['entreprises' => $entreprises]);
    }

}
