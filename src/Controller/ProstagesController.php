<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Form\EntrepriseType;
use App\Form\StageType;

class ProstagesController extends AbstractController
{
    /**
     * @Route("/", name="prostages_stages")
     */
    public function listerStages(): Response
    {
        //Récupérer le repository de l'entité Stage
        $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

        //Récupérer les stages enregistrés en BD
        $stages = $repositoryStage->findAllStagesEtEntreprisesEtFormations();

        //Envoyer les stages récupérés à la vue chargée de les afficher
        return $this->render('prostages/stages.html.twig', [
            'stages' => $stages,
        ]);
    }

    /**
     * @Route("/user/ajouter/stage", name="prostages_ajouterStage")
     */
    public function ajoutStage(Request $requeteHttp, EntityManagerInterface $manager)
    {
        //création d'un nouveau stage
        $stage = new Stage();

        //création d'un objet formulaire pour saisir un stage
        $formulaireStage = $this -> createForm(StageType::class, $stage);

        //récupération des données dans $formulaireStage si elles ont été soumises
        $formulaireStage->handleRequest($requeteHttp);

        //traiter les données du formulaire s'il a été soumis
        if($formulaireStage->isSubmitted() && $formulaireStage->isValid())
        {
            //enregistrer le stage en BD
            $manager->persist($stage);
            $manager->flush();

            //rediriger l'utilisateur vers la page affichant la liste des stages
            return $this->redirectToRoute('prostages_stages');
        }


        //affichage de la page d'ajout d'un stage
        return $this->render('prostages/formulaireAjoutStage.html.twig', [
            'vueFormulaireStage' => $formulaireStage->createView(),
            'action' => 'ajouter'
        ]);
    }

    /**
     * @Route("/entreprises", name="prostages_entreprises")
     */
    public function listerEntreprises(): Response
    {
        //Récupérer le repository de l'entité Entreprise
        $repositoryEntreprises = $this->getDoctrine()->getRepository(Entreprise::class);

        //Récupérer les entreprises enregistrés en BD
        $entreprises = $repositoryEntreprises->findAll();

        //Envoyer les entreprises récupérés à la vue chargée de les afficher
        return $this->render('prostages/entreprises.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    /**
     * @Route("/formations", name="prostages_formations")
     */
    public function listerFormations(): Response
    {
        //Récupérer le repository de l'entité Formation
        $repositoryFormations = $this->getDoctrine()->getRepository(Formation::class);

        //Récupérer les entreprises enregistrés en BD
        $formations = $repositoryFormations->findAll();

        //Envoyer les formations récupérés à la vue chargée de les afficher
        return $this->render('prostages/formations.html.twig', [
            'formations' => $formations,
        ]);
    }

    /**
     * @Route("/stage/{id}", name="prostages_stageDetails")
     */
    public function afficherStageDetails($id): Response
    {
        //Récupérer le repository de l'entité Stage
        $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

        //Récupérer les stages enregistrés en BD
        $stage = $repositoryStage->findStageById($id);

        return $this->render('prostages/stageDetails.html.twig', [
            'stage' => $stage,
        ]);
    }

    /**
     * @Route("/admin/ajouter/entreprise", name="prostages_ajouterEntreprise")
     */
    public function ajoutEntreprise(Request $requeteHttp, EntityManagerInterface $manager)
    {
        //création d'une nouvelle entreprise
        $entreprise = new Entreprise();

        //création d'un objet formulaire pour saisir une entreprise
        $formulaireEntreprise = $this -> createForm(EntrepriseType::class, $entreprise);

        //récupération des données dans $entreprise si elles ont été soumises
        $formulaireEntreprise->handleRequest($requeteHttp);

        //traiter les données du formulaire s'il a été soumis
        if($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid())
        {
            //enregistrer l'entreprise en BD
            $manager->persist($entreprise);
            $manager->flush();

            //rediriger l'utilisateur vers la page affichant la liste des entreprises
            return $this->redirectToRoute('prostages_entreprises');
        }


        //affichage de la page d'ajout d'une entreprise
        return $this->render('prostages/formulaireAjoutModifEntreprise.html.twig', [
            'vueFormulaireEntreprise' => $formulaireEntreprise->createView(),
            'action' => 'ajouter'
        ]);
    }

    /**
     * @Route("/admin/modifier/entreprises/{id}", name="prostages_modifierEntreprise")
     */
    public function modificationEntreprise(Request $requeteHttp, EntityManagerInterface $manager, Entreprise $entreprise)
    {
        //création d'un objet formulaire pour modifier une entreprise
        $formulaireEntreprise = $this -> createForm(EntrepriseType::class, $entreprise);

        //récupération des données dans $entreprise si elles ont été soumises
        $formulaireEntreprise->handleRequest($requeteHttp);

        //traiter les données du formulaire s'il a été soumis
        if($formulaireEntreprise->isSubmitted() && $formulaireEntreprise->isValid())
        {
            //enregistrer l'entreprise en BD
            $manager->persist($entreprise);
            $manager->flush();

            //rediriger l'utilisateur vers la page affichant la liste des entreprises
            return $this->redirectToRoute('prostages_entreprises');
        }


        //affichage de la page d'ajout d'une entreprise
        return $this->render('prostages/formulaireAjoutModifEntreprise.html.twig', [
            'vueFormulaireEntreprise' => $formulaireEntreprise->createView(),
            'action' => 'modifier'
        ]);
    }

    /**
     * @Route("/entreprises/{id}", name="prostages_stagesParEntreprise")
     */
    public function afficherStagesParEntreprise($id): Response
    {
        //Récupérer le repository de l'entité Entreprise
        $repositoryEntreprise = $this->getDoctrine()->getRepository(Entreprise::class);

        //Récupérer les stages enregistrés en BD
        $entreprise = $repositoryEntreprise->findByEntrepriseId($id);

        return $this->render('prostages/stagesParEntreprise.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }
    
    /**
     * @Route("/formations/{id}", name="prostages_stagesParFormation")
     */
    public function afficherStagesParFormation($id): Response
    {
        //Récupérer le repository de l'entité Formation
        $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);

        //Récupérer les stages enregistrés en BD
        $formation = $repositoryFormation->findByFormationId($id);

        return $this->render('prostages/stagesParFormation.html.twig', [
            'formation' => $formation,
        ]);
    }
}
