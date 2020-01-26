<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;

class PanierController extends AbstractController {
    public function index(PanierService $panier) {
        $commande = $panier->getContenu();
        $total = $panier->getTotal();
        $nbProduit = $panier->getNbProduits();
        $total = $panier->getTotal();
        return $this->render('Panier/index.html.twig',
                [
                    'commande' => $commande,
                    'total' => $total,
                    'nbProduit' => $nbProduit,
                    'total' => $total
                ]);
    }
    
    public function ajouter(PanierService $panier, ProduitRepository $produitRepository, $idProduit, $quantite) {
        $produit = $produitRepository->findOneById($idProduit);
 
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        
        $panier->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier_index');
    }
    
    public function enlever(PanierService $panier, ProduitRepository $produitRepository, $idProduit, $quantite) {
        $produit = $produitRepository->findOneById($idProduit);
 
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        
        $panier->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier_index');
    }
    
    public function supprimer(PanierService $panier, ProduitRepository $produitRepository, $idProduit) {
        $produit = $produitRepository->findOneById($idProduit);
 
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        
        $panier->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier_index');
    }
    
    public function vider(PanierService $panier) {       
        $panier->vider();
        return $this->redirectToRoute('panier_index');
    }
    
    public function validation(PanierService $panier) {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commande = $panier->panierToCommande($user);
        $ligneCommandes = $commande->getLigneCommandes();
        
        // Insertion de la commande
        $em->persist($commande);
        
        // Insertion des lignes commandes
        foreach($ligneCommandes->toArray() as $ligne) {
            $em->persist($ligne);
        }
       
        $em->flush();
        return $this->redirectToRoute('usager_listeCommandes');
    }
    
    public function listeCommandes(CommandeRepository $commandeRepository) {
        $user = $this->getUser();
        $commandes = $commandeRepository->findBy(array('usager' => $user));
        return $this->render('Panier/listeCommandes.html.twig',
                [
                    'commandes' => $commandes
                ]);
    }
}