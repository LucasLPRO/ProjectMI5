<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\BoutiqueService;

class BoutiqueController extends AbstractController {
    
     public function index(BoutiqueService $boutique) {
        $categories = $boutique->findAllCategories();
        return $this->render('Boutique/boutique.html.twig', 
                [
                    'categories' => $categories,
                ]);
    }
    
    public function rayon($idCategorie, BoutiqueService $boutique) {
        $produits = $boutique->findProduitsByCategorie($idCategorie);
        $categorie = $boutique->findCategorieById($idCategorie);
        return $this->render('Boutique/rayon.html.twig',
                [
                    'produits' => $produits,
                    'categorie' => $categorie,
                ]);
    }
    
    public function chercher($libelle, BoutiqueService $boutique) {
       $produits = $boutique->findProduitsByLibelleOrTexte($libelle);
       return $this->render('Boutique/rayon.html.twig',
               [
                   'produits' => $produits,
                   'libelle' => $libelle
               ]);
    }
}