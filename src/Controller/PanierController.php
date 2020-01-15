<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;
use App\Repository\ProduitRepository;

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
}