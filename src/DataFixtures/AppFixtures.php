<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Formation;
use App\Entity\Stage;
use App\Entity\Entreprise;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      //Creation de generateurs de donnees Faker
      $faker = \Faker\Factory::create('fr_FR');

      /**************************************
      ****   CREATION DES ENTREPRISES    ****
      **************************************/

      $safran = new Entreprise();
      $safran -> setNom("Safran");
      $safran -> setAdresse($faker->address());
      $safran -> setDomaineActivite("Aéronautique");
      $safran -> setNumTel($faker->regexify("0[1-9]{9}"));
      $safran -> setSiteWeb($faker->url());

      $capgemini = new Entreprise();
      $capgemini -> setNom("Capgemini");
      $capgemini -> setAdresse($faker->address());
      $capgemini -> setDomaineActivite("Services informatiques");
      $capgemini -> setNumTel($faker->regexify("0[1-9]{9}"));
      $capgemini -> setSiteWeb($faker->url());

      $sopra = new Entreprise();
      $sopra -> setNom("Sopra Steria");
      $sopra -> setAdresse($faker->address());
      $sopra -> setDomaineActivite("Transformations digitales");
      $sopra -> setNumTel($faker->regexify("0[1-9]{9}"));
      $sopra -> setSiteWeb($faker->url());

      $seriel = new Entreprise();
      $seriel -> setNom("Seriel");
      $seriel -> setAdresse($faker->address());
      $seriel -> setDomaineActivite("Services informatiques");
      $seriel -> setNumTel($faker->regexify("0[1-9]{9}"));
      $seriel -> setSiteWeb($faker->url());


      /* On regroupe les objets "entreprises" dans un tableau
      pour pouvoir s'y référer au moment de la création d'une ressource particulière */
      $tableauEntreprise = array($safran, $capgemini, $sopra, $seriel);

      // Mise en persistence des objets Entreprise
      foreach ($tableauEntreprise as $tabEntreprise)
      {
        $manager->persist($tabEntreprise);
      }

      /************************************************************
      ****    Liste des formations concernés par des stages    ****
      ************************************************************/
      $formationStage = array(
        "dutInfo" => "DUT Informatique",
        "dutGEA" => "DUT Gestion des entreprises et des administrations",
        "dutTC" => "DUT Techniques de Commercialisation",
        "dutGIM" => "DUT Génie Industriel et Maintenance",
        "licPA" => "Licence Programmation Avancée",
        "licMN" => "Licence des Métiers du Numériques"
      );

      /************************************************************
      ****            Liste des intitulés de stages            ****
      ************************************************************/
      $intituleStage = array(
        "Stage développeur fullStack",
        "Stage développeur Php",
        "Stage développeur d'application Web",
        "Stage développeur Python",
        "Stage intégrateur réseau",
        "Stage Programmation Logiciel"
      );

      /************************************************************
      ****    CREATION DES FORMATIONS ET DES STAGES ASSOCIES   ****
      ************************************************************/
      //indice pour la création d'un $tableauEntreprise
      $i=0;

      foreach ($formationStage as $codeFormation =>$titreFormation){
          // création d'une nouvelle formation
           $formation = new Formation();
           //Définition du nom de la formation
           $formation->setIntitule($titreFormation);
           //Définition du niveau de la formation
           $formation->setNiveau($faker->regexify("[1-2]e année"));
           //Définition de la ville de la formation
           $formation->setVille($faker->city());
           //Enregistrement de la formation créé
           $manager->persist($formation);

           //Création d'un tableau contenant les formations
           $tabformation[$i] = array ($formation);
           $i++;


       // ***** Création de plusieurs Stages associées au formations
       $nbStages = $faker->numberBetween($min = 0, $max = 3);
       for ($numStage = 0; $numStage < $nbStages; $numStage++){

          $numIntituleStage = $faker->numberBetween($min = 0, $max = 5);

          $stage = new Stage();
          $stage -> setIntitule($intituleStage[$numIntituleStage]);
          $stage -> setDescription($faker->realText($maxNbChars = 200));
          $stage -> setDateDebut($faker->DateTime());
          $stage -> setDuree($faker->regexify("[1-6] mois"));
          $stage -> setCompetencesRequises($faker->text($maxNbChars = 200) );
          $stage -> setExperiencesRequises($faker->text($maxNbChars = 200) );
          $stage -> setEmail($faker->email());



          //création relation Stage -> Formation
          $stage -> addFormation($formation);

          /****Definir et maj l'entreprise ****/
          //selectionner une entreprise au hasard parmi les 5 enregistrées dans $tableauEntreprise
          $numEntreprise = $faker->numberBetween($min = 0, $max = 3);
          //creation Stage -> Entreprise
          $stage->setEntreprise($tableauEntreprise[$numEntreprise]);
          //création relation Entreprise -> Stage
          $tableauEntreprise[$numEntreprise] -> addEntreprise($stage);

          //Persister les objets modifiés
          $manager->persist($stage);
          $manager->persist($tableauEntreprise[$numEntreprise]);

          }


}//fin du foreach

       // Envoyer les données en BD
        $manager->flush();
    }
}
