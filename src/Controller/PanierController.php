<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\BoutiqueService;
use App\Service\PanierService;

class PanierController extends AbstractController {
    public function index(PanierService $panier) {
        $commande = $panier->getContenu();
        return $this->render('Panier/index.html.twig');
    }
}