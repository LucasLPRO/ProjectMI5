<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;

class DefaultController extends AbstractController {
    public function index() {
        return $this->render('Default/home.html.twig');
    }
    
    public function contact() {
        return $this->render('Default/contact.html.twig');
    }
    
    public function navBar(PanierService $panier) {
        $nbProduit = $panier->getNbProduits();
        
        return $this->render('navbar.html.twig',
                [
                    'nbProduit' => $nbProduit
                ]);
    }
}