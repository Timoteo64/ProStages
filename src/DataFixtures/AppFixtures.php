<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Formation;
use App\Entity\Entreprise;
use App\Entity\Stage;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //Création de 2 utilisateurs de test
        $utilisateur = new User();
        $utilisateur->setNom("userNAME");
        $utilisateur->setUsername("user");
        $utilisateur->setRoles(['ROLE_USER']);
        $utilisateur->setPassword('$2y$10$Zz/.jMdREJfZZU9z.M.4CuGF791VYnsDrYNqY24U573cLTe.E3K.S');
        $manager->persist($utilisateur);

        $admin = new User();
        $admin->setNom("adminNAME");
        $admin->setUsername("admin");
        $admin->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $admin->setPassword('$2y$10$flXEkuNt1ewzaJSiEopeKeKOG.GaforJ453k5nx3r10qq8cSoWeoi');
        $manager->persist($admin);

        //Création d'un générateur de données faker
        $faker = \Faker\Factory::create('fr_FR');
        // Création des formations
        $dutInfo = new Formation();
        $dutInfo->setNomCourt("DUT Info");
        $dutInfo->setNomLong("Diplôme Universitaire Technologique Informatique");
        $dutGim = new Formation();
        $dutGim->setNomCourt("DUT Gim");
        $dutGim->setNomLong("Diplôme Universitaire Technologique génie industriel et maintenance");
        $enit = new Formation();
        $enit->setNomCourt("ENIT");
        $enit->setNomLong("École Nationale d’Ingénieurs de Tarbes");
        $isabtp = new Formation();
        $isabtp->setNomCourt("ISA BTP");
        $isabtp->setNomLong("Institut Supérieur Aquitain du Bâtiment et des Travaux Publics");
        $tableauFormations = array($dutInfo, $dutGim, $enit, $isabtp);
        foreach($tableauFormations as $formation)
        {
            $manager->persist($formation);
        }
        //Création des entreprises


        $nbEntreprises = 20;

        for($i = 0; $i < $nbEntreprises; $i++){
            $entreprise = new Entreprise();
            $entreprise->setNom($faker->company()); 
            $entreprise->setAdresse($faker->address());
            $entreprise->setActivite($faker->catchPhrase()); 
            $entreprise->setUrlsite($faker->url()); 


            $nbStages = $faker->numberBetween(1,6);

            for($j = 0; $j < $nbStages; $j++){
                $stage = new Stage();
                $stage->setTitre($faker->jobTitle());      
                $stage->setDescMission($faker->realTextBetween(100,200)); 
                $stage->setEmailContact($faker->companyEmail()); 
                $stage->setEntreprises($entreprise); 


                $nbFormationPourUnStage = $faker->numberBetween(1,4);
                for($k = 0; $k < $nbFormationPourUnStage; $k++){
                    $nbIndiceFormation = $faker->unique()->numberBetween(0,3); 
                    $stage->addFormation($tableauFormations[$nbIndiceFormation]);
                }
                $manager->persist($stage);
                $faker->unique(true); 
            }
            $manager->persist($entreprise);
        }
        $manager->flush();
    }
}
