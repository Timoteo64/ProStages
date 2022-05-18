<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }




    /**
     * @Route("/signup", name="app_ajouterNouvelUtilisateur")
     */
    public function ajoutNouvelUtilisateur(Request $requeteHttp, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        //création d'un nouvel utilisateur
        $utilisateur = new User();

        //création d'un objet formulaire pour saisir un utilisateur
        $formulaireNouvelUtilisateur = $this->createForm(UserType::class, $utilisateur);

        //récupération des données dans $formulaireNouvelUtilisateur si elles ont été soumises
        $formulaireNouvelUtilisateur->handleRequest($requeteHttp);

        //traiter les données du formulaire s'il a été soumis
        if ($formulaireNouvelUtilisateur->isSubmitted() && $formulaireNouvelUtilisateur->isValid())
        {
            //Attribuer un rôle à l'utilisateur
            $utilisateur->setRoles(['ROLE_USER']);

            //Encoder le mot de passe utilisateur
            $encodagePassword = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($encodagePassword);

            //enregistrer l'utilisateur en BD
            $manager->persist($utilisateur);
            $manager->flush();

            //rediriger l'utilisateur vers la page affichant la liste des stages
            return $this->redirectToRoute('app_login');
        }


        //affichage de la page d'ajout d'un utilisateur
        return $this->render('security/formulaireAjoutNouvelUtilisateur.html.twig', [
            'vueFormulaireNouvelUtilisateur' => $formulaireNouvelUtilisateur->createView()
        ]);
    }



}
