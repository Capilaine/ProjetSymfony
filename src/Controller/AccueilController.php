<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commentaire;
use App\Form\AjoutProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Security\CustomAuthenticationSuccessHandler;
use ContainerFOAKfMN\getAjoutProduitService;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;

class AccueilController extends AbstractController
{



    // Route vers l'accueil 


#[Route('/', name: 'accueil')]
public function accueil(EntityManagerInterface $manager): Response
{
    $produitRepository = $manager->getRepository(Produit::class);
    $derniersProduits = $produitRepository->findBy([], ['id' => 'DESC'], 5);

    return $this->render('accueil/accueil.html.twig', [
        'derniersProduits' => $derniersProduits,
    ]);
}



    // Route vers la liste des produits


    #[Route('/liste-produits', name: 'liste_produits')]
public function listeProduits(EntityManagerInterface $manager): Response
{
    $produitRepository = $manager->getRepository(Produit::class);
    $tousProduits = $produitRepository->findAll();

    return $this->render('accueil/listeProduits.html.twig', [
        'tousProduits' => $tousProduits,
    ]);
}



    // Route vers un produit trouvé par ID


    #[Route('/produit/{id}', name: 'details_produit')]
    public function detailsProduit(EntityManagerInterface $entityManager, $id): Response
    {
        $produitRepository = $entityManager->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        $commentaire = $produit->getCommentaire();

        return $this->render('accueil/infosProduit.html.twig', [
            'produit' => $produit,
            'commentaire' => $commentaire,
        ]);
    }



    // "Route" vers l'ajout d'un commentaire côté internaute


    #[Route('/produit/{id}/ajout-commentaire', name: 'ajout_commentaire', methods: ['POST'])]
    public function addComment(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        $titre = $request->request->get('titre');
        $contenu = $request->request->get('contenu');

        if (empty($titre) || empty($contenu)) {
            $this->addFlash('error', 'Veuillez remplir tous les champs s\'il vous plaît.');
    
            return $this->redirectToRoute('details_produit', ['id' => $id]);
        } else {
        $commentaire = new Commentaire();
        $commentaire->setTitre($titre);
        $commentaire->setContenu($contenu);
        $commentaire->setProduit($produit);

        $entityManager->persist($commentaire);
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire ajouté avec succès.');

        return $this->redirectToRoute('details_produit', ['id' => $id]);
        }
    }



    // Route vers le login


    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, CustomAuthenticationSuccessHandler $successHandler): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('accueil/formulaireConnexion.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }



    // Route vers le logout


    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
    
    }



    // Route vers la liste des produits côté admin


    #[Route('/admin/liste-produits', name: 'liste_produits_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function listeProduitsAdmin(EntityManagerInterface $manager): Response
    {
        $produitRepository = $manager->getRepository(Produit::class);
        $tousProduits = $produitRepository->findAll();

        return $this->render('accueil/listeProduitsAdmin.html.twig', [
            'tousProduits' => $tousProduits,
        ]);
    }


    // Route d'un produit trouvé par ID côté admin


    #[Route('/admin/produit/{id}', name: 'infos_produit_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function infosProduitAdmin(EntityManagerInterface $entityManager, $id): Response
    {
        $produitRepository = $entityManager->getRepository(Produit::class);
        $produit = $produitRepository->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $commentaire = $produit->getCommentaire();

        return $this->render('accueil/infosProduitAdmin.html.twig', [
            'produit' => $produit,
            'commentaire' => $commentaire,
        ]);
    }



    // "Route" vers la modification d'un article côté admin

    #[Route('/admin/produit/{id}/modifier', name: 'modifier_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Le produit avec l\'identifiant ' . $id . ' n\'existe pas.');
        }

        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $stock = $request->request->get('stock');

        $produit->setNom($nom);
        $produit->setDescription($description);
        $produit->setStock($stock);

        $imageFile = $request->files->get('image');
        if ($imageFile) {

        }

        $entityManager->flush();

        return $this->redirectToRoute('infos_produit_admin', ['id' => $produit->getId()]);
    }



    // Route pour ajouter un produit


    #[Route('/admin/produit/ajouter', name: 'ajouter_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function ajoutProduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nom = $request->request->get('nom');
        $description = $request->request->get('description');
        $stock = $request->request->get('stock');

        $produit = new Produit();
        $produit->setNom($nom);
        $produit->setDescription($description);
        $produit->setStock($stock);

        try {
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Le produit a été ajouté avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'ajout du produit.');
            // Optionnel : Afficher l'erreur pour le débogage
            // dump($e->getMessage());
        }

        return $this->redirectToRoute('liste_produits_admin');
    }
}