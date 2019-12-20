<?php

// src/Service/PanierService.php
namespace App\Service;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\BoutiqueService;
// Service pour manipuler le panier et le stocker en session
class PanierService {
    ////////////////////////////////////////////////////////////////////////////
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier
    private $session;  // Le service Session
    private $boutique; // Le service Boutique
    private $panier;   // Tableau associatif idProduit => quantite
	                   //  donc $this->panier[$i] = quantite du produit dont l'id = $i
    // constructeur du service
    public function __construct(SessionInterface $session, BoutiqueService $boutique) {
        // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->session = $session;
        // Récupération du panier en session s'il existe, init. à vide sinon
        if ($session->has('PANIER_SESSION')) {
            $this->panier = $session->get('PANIER_SESSION', array()); // initialisation du Panier
        } else {
            $this->panier = array();
        }
    }
    
    // getContenu renvoie le contenu du panier
    // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu() { 
        $contenu = array();
        foreach($this->panier as $idProduit => $quantite) {
            $produit = $this->boutique->findProduitById($idProduit);
            $contenu[] = array("produit" => $produit, "quantite" => $quantite);
        }
        return $contenu;
    }
    
    // getTotal renvoie le montant total du panier
    public function getTotal() { 
        $contenu = $this->getContenu();
        $total = 0;
        foreach($contenu as $item) {
            $total += $item["produit"]["prix"] * $item["quantite"];
        }
        return $total;
    }
    
    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbProduits() {
        $nbProduits = 0;
        foreach($this->panier as $idProduit => $quantite) {
            $nbProduits += $quantite;
        }
        return $nbProduits;        
    }
    
    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite 
    public function ajouterProduit(int $idProduit, int $quantite = 1) {        
        if (isset($this->panier[$idProduit])) {
            $this->panier[$idProduit] += $quantite;
        } else {
            $this->panier[$idProduit] = $quantite;
        }
        $this->session->set('PANIER_SESSION', $this->panier);
    }
    
    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite 
    public function enleverProduit(int $idProduit, int $quantite = 1) { 
        //$qte = $this->panier[$idProduit] - $quantite;
        $this->panier[$idProduit] -= $quantite;
        $this->session->set('PANIER_SESSION', $this->panier);
        if ($this->panier[$idProduit] == 0) {
            $this->supprimerProduit($idProduit);
        }
    } 
    
    // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerProduit(int $idProduit) { 
        unset($this->panier[$idProduit]);
        $this->session->set('PANIER_SESSION', $this->panier);
    }
    
    // vider vide complètement le panier
    public function vider() { 
        $this->panier = array();
        $this->session->set('PANIER_SESSION', $this->panier);
    }
}
