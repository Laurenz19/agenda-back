<?php

namespace App\DataFixtures;

use App\Entity\Candidat;
use App\Entity\Travail;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for($c = 0; $c < 10; $c ++){
            $candidat = new Candidat();
            $candidat->setNomComplet($faker->name)
                     ->setDateNais(new DateTime($faker->date()))
                     ->setContact($faker->phoneNumber)
                     ->setEmail($faker->email)
                     ->setAdresse($faker->address)
                     ->setCompetences(["Vuejs", "Symfony"]);

                     $manager->persist($candidat);
        }


        for($t = 0; $t<2; $t++){
            $travail = new Travail();
            $travail->setDescription($faker->jobTitle);
            $manager->persist($travail);
        }

        $manager->flush();
    }
}
