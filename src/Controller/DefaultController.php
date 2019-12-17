<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController {
    public function index() {
//        $translator = $this->get('translator');
//        $texteTraduit = $translator->trans("Ici rien à vendre, c'est juste pour apprendre");
        return $this->render('Default/home.html.twig');
    }
    
    public function contact() {
        return $this->render('Default/contact.html.twig');
    }
}