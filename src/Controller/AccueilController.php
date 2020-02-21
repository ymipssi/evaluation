<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $panier = new Panier();
        $form = $this->createForm(PanierFormType::class, $panier);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($panier);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté au panier');
        }

        $paniers = $em->getRepository(Panier::class)->findAll();

        return $this->render('accueil/index.html.twig', [
            'paniers' => $paniers,
            'formAjoutProduitDansPanier' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="supprimer_produit_du_panier")
     */
    public function delete(Panier $panier=null){
        if($panier != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($panier);
            $em->flush();

            $this->addFlash('danger', 'Produit supprimé du panier');
        }
        else{
            $this->addFlash('danger', 'Produit introuvable');
        }
        
        return $this->redirectToRoute('accueil');
    }
}

