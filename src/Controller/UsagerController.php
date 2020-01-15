<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\UsagerType;
use App\Repository\UsagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/usager")
 */
class UsagerController extends AbstractController
{
    public function index(UsagerRepository $usagerRepository, SessionInterface $session): Response
    {
        if ($session->has('USAGER_SESSION')) {
            $usager = $usagerRepository->findOneById($session->get('USAGER_SESSION'));
            return $this->render('usager/index.html.twig', [
                'usager' => $usager
            ]);
        } else {
            return $this->render('usager/index.html.twig');
        }
    }

    public function new(Request $request, SessionInterface $session): Response
    {
        $usager = new Usager();
        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usager);
            $entityManager->flush();
            
            $session->set('USAGER_SESSION', $usager->getId());
            return $this->redirectToRoute('usager_accueil');
        }

        return $this->render('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form->createView(),
        ]);
    }
}
