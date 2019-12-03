<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Client;
use App\Form\CategorieType;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index()
    {
        $repository=$this->getDoctrine()->getRepository(Client::class);

        $clients=$repository->findAll();

        return $this->render('actions/index.html.twig', [
            "clients"=>$clients,
        ]);
    }
    /**
     * @Route("/clients/ajouter", name="ajouter_categories")
     */
    public function ajouter(Request $request)
    {
        $client=new Client();

        //creation du formulaire
        $formulaire=$this->createForm(ClientType::class, $client);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récupérer l'entity manager (sorte de connexion à la BDD)
            $em=$this->getDoctrine()->getManager();

            //je dis au manager que je veux ajouter la catégorie dans la BDD
            $em->persist($client);

            $em->flush();

            return $this->redirectToRoute("client");
        }
        return $this->render('actions/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Ajouter une catégorie"
        ]);
    }
    /**
     * @Route("/clients/modifier/{id}", name="modifier_categories")
     */
    public function modifier(int $id, \Symfony\Component\HttpFoundation\Request $request)
    {

        $repository=$this->getDoctrine()->getRepository(Client::class);
        $categorie=$repository->find($id);

        //création du formulaire
        $formulaire=$this->createForm(ClientType::class, $categorie);

        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid())
        {
            //récupérer l'entity manager (sorte de connexion à la BDD)
            $em=$this->getDoctrine()->getManager();

            //je dis au manager que je veux ajouter la catégorie dans la BDD
            $em->persist($categorie);

            $em->flush();

            return $this->redirectToRoute("client");
        }

        return $this->render('actions/formulaire.html.twig', [
            "formulaire"=>$formulaire->createView(),
            "h1"=>"Modification de la catégorie <i>".$categorie->GetNom()."</i>",
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_categories")
     *
     *
     *
     *
     *
     */
    public function delete(int $id, \Symfony\Component\HttpFoundation\Request $request)
    {

        $rep = $this->getDoctrine()->getRepository(Client::class);
        $cate = $rep->find($id);

        //création du formulaire
        //$formulaire1 = $this->createForm(CategorieType::class, $cate);


        $formulaire1 = $this->createFormBuilder()->add("submit",SubmitType::class,["label" => "OK", "attr"=>["class"=>"btn btn-success"]])->getForm();
        $formulaire1->handleRequest($request);

        if ($formulaire1->isSubmitted() && $formulaire1->isValid()) {
            //récupérer l'entity manager (sorte de connexion à la BDD)
            $em = $this->getDoctrine()->getManager();

            //je dis au manager que je veux supprimer la categorie de la BDD
            $em->remove($cate);

            $em->flush();

            return $this->redirectToRoute("client");
        }


        return $this->render('actions/formulaire.html.twig', [
            "formulaire"=>$formulaire1->createView(),
            "h1"=>"Modification de la catégorie <i>".$cate->GetNom()."</i>",
        ]);

    }

}
