<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\AjoutProduitFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produits")
 */

class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="produits")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = new Produit();
        $form = $this->createForm(AjoutProduitFormType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté');
        }

        $produits = $em->getRepository(Produit::class)->findAll();

        return $this->render('produits/index.html.twig', [
            'produits' => $produits,
            'formAjoutProduit' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="produit")
     */
    public function produit(Produit $produit=null, Request $request){
        if($produit != null){

            $form = $this->createForm(AjoutProduitFormType::class, $produit);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($produit);
                $em->flush();

                $this->addFlash('primary', 'Produit mis à jour');
            }

            return $this->render('produits/produit.html.twig', [
                'produit' => $produit,
                'edit_produit' => $form->createView()
            ]);
        }
        else{
            $this->addFlash('danger', 'Produit introuvable');
            return $this->redirectToRoute('produits');
        }
    }

    /**
     * @Route("/delete/{id}", name="supprimer_produit")
     */
    public function delete(Produit $produit=null){
        if($produit != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();

            $this->addFlash('danger', 'Produit supprimé');
        }
        else{
            $this->addFlash('danger', 'Produit introuvable');
        }
        
        return $this->redirectToRoute('produits');
    }

}
