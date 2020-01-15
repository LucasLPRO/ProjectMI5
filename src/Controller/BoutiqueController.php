<?php //

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;

class BoutiqueController extends AbstractController {
    
     public function index(CategorieRepository $categorieRepository) {
        $categories = $categorieRepository->findAll();
        return $this->render('Boutique/boutique.html.twig', 
                [
                    'categories' => $categories,
                ]);
    }
    
    public function rayon($idCategorie, CategorieRepository $categorieRepository, ProduitRepository $produitRepository) {
        $produits = $produitRepository->findByCategorie($idCategorie);
        $categorie = $categorieRepository->findOneById($idCategorie);
        return $this->render('Boutique/rayon.html.twig',
                [
                    'produits' => $produits,
                    'categorie' => $categorie,
                ]);
    }
    
    public function chercher($libelle, ProduitRepository $produitRepository) {
        $produits = $produitRepository->findProduitsByLibelleOrTexte($libelle);
        return $this->render('Boutique/rayon.html.twig',
               [
                   'produits' => $produits,
                   'libelle' => $libelle
               ]);
    }
}