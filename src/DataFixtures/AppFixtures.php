<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Entity\Admin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        // Création d'un nouvel admin
        $admin = new Admin();
        $admin->setEmail('melaine.fritot@gmail.com');
        
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'MotDePasse54321://'
        );
        $admin->setPassword($hashedPassword);
        
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Création des commentaires et produits
        $commentaire1 = new Commentaire();
        $commentaire1->setTitre($faker->sentence());
        $commentaire1->setContenu($faker->realText());
        $manager->persist($commentaire1);
        $commentaire2 = new Commentaire();
        $commentaire2->setTitre($faker->sentence());
        $commentaire2->setContenu($faker->realText());
        $manager->persist($commentaire2);
        $commentaire3 = new Commentaire();
        $commentaire3->setTitre($faker->sentence());
        $commentaire3->setContenu($faker->realText());
        $manager->persist($commentaire3);
        $produit1 = new Produit();
        $produit1->setNom($faker->word);
        $produit1->setDescription($faker->paragraph());
        $produit1->setImage('img/produit1.jpg');
        $produit1->setStock($faker->numberBetween(0, 200));
        $produit1->addCommentaire($commentaire1);
        $produit1->addCommentaire($commentaire2);
        $produit1->addCommentaire($commentaire3);
        $manager->persist($produit1);

        $commentaire4 = new Commentaire();
        $commentaire4->setTitre($faker->sentence());
        $commentaire4->setContenu($faker->realText());
        $manager->persist($commentaire4);
        $commentaire5 = new Commentaire();
        $commentaire5->setTitre($faker->sentence());
        $commentaire5->setContenu($faker->realText());
        $manager->persist($commentaire5);
        $produit2 = new Produit();
        $produit2->setNom($faker->word);
        $produit2->setDescription($faker->paragraph());
        $produit2->setImage('img/produit2.jpg');
        $produit2->setStock($faker->numberBetween(0, 200));
        $produit2->addCommentaire($commentaire4);
        $produit2->addCommentaire($commentaire5);
        $manager->persist($produit2);

        $commentaire6 = new Commentaire();
        $commentaire6->setTitre($faker->sentence());
        $commentaire6->setContenu($faker->realText());
        $manager->persist($commentaire6);
        $commentaire7 = new Commentaire();
        $commentaire7->setTitre($faker->sentence());
        $commentaire7->setContenu($faker->realText());
        $manager->persist($commentaire7);
        $commentaire8 = new Commentaire();
        $commentaire8->setTitre($faker->sentence());
        $commentaire8->setContenu($faker->realText());
        $manager->persist($commentaire8);
        $produit3 = new Produit();
        $produit3->setNom($faker->word);
        $produit3->setDescription($faker->paragraph());
        $produit3->setImage('img/produit3.webp');
        $produit3->setStock($faker->numberBetween(0, 200));
        $produit3->addCommentaire($commentaire6);
        $produit3->addCommentaire($commentaire7);
        $produit3->addCommentaire($commentaire8);
        $manager->persist($produit3);

        $produit4 = new Produit();
        $produit4->setNom($faker->word);
        $produit4->setDescription($faker->paragraph());
        $produit4->setImage('img/produit4.jpg');
        $produit4->setStock($faker->numberBetween(0, 200));
        $manager->persist($produit4);

        $commentaire9 = new Commentaire();
        $commentaire9->setTitre($faker->sentence());
        $commentaire9->setContenu($faker->realText());
        $manager->persist($commentaire9);
        $commentaire10 = new Commentaire();
        $commentaire10->setTitre($faker->sentence());
        $commentaire10->setContenu($faker->realText());
        $manager->persist($commentaire10);
        $commentaire11 = new Commentaire();
        $commentaire11->setTitre($faker->sentence());
        $commentaire11->setContenu($faker->realText());
        $manager->persist($commentaire11);
        $commentaire12 = new Commentaire();
        $commentaire12->setTitre($faker->sentence());
        $commentaire12->setContenu($faker->realText());
        $manager->persist($commentaire12);
        $produit5 = new Produit();
        $produit5->setNom($faker->word);
        $produit5->setDescription($faker->paragraph());
        $produit5->setImage('img/produit5.webp');
        $produit5->setStock($faker->numberBetween(0, 200));
        $produit5->addCommentaire($commentaire9);
        $produit5->addCommentaire($commentaire10);
        $produit5->addCommentaire($commentaire11);
        $produit5->addCommentaire($commentaire12);
        $manager->persist($produit5);

        $commentaire13 = new Commentaire();
        $commentaire13->setTitre($faker->sentence());
        $commentaire13->setContenu($faker->realText());
        $manager->persist($commentaire13);
        $produit6 = new Produit();
        $produit6->setNom($faker->word);
        $produit6->setDescription($faker->paragraph());
        $produit6->setImage('img/produit6.webp');
        $produit6->setStock($faker->numberBetween(0, 200));
        $produit6->addCommentaire($commentaire13);
        $manager->persist($produit6);

        $manager->flush();
    }
}
