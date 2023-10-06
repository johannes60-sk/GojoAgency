<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class AdminPropertyController extends AbstractController{

    private $repository;
    private $entityManager;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }


    #[Route("/admin", name: "admin.property.index")]

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // $properties =  $this->repository->findAll();


        $properties = $paginator->paginate(

            $this->repository->findAllVisibleQuery(),
            $request->query->getInt('page', 1),
            12
        );
        

        return $this->render('admin/property/index.html.twig',[
            'properties' => $properties
        ]);
    }


   #[Route("/admin/property/create", name: "admin.property.new")]
    public function new(Request $request) {

        $property = new Property();

        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $property = $form->getData();

            $this->entityManager->persist($property);  // ici comme j'ai creer l'entity property manuelement ($property = new Property();) il n'est pas suvie par l'entity manager donc on doit d'abord le persister avant de flush()

            $this->entityManager->flush();  

            $this->addFlash('success',  'Bien creer avec success');


            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


    #[Route("/admin/property/{id}", name: "admin.property.edit", methods: ['GET', 'POST'])]

    public function edit(Request $request, Property $property, $id)
    {

        $property = $this->repository->find($id);

        $form = $this->createForm(PropertyType::class, $property);  // prend en params le type du form et l'entite qu'il doit utiliser pour generer et remplir le form

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
           
            $this->entityManager->flush();

            $this->addFlash('success',  'Bien modifier avec success');

            return  $this->redirectToRoute('admin.property.index');
        } 

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/property/delete/{id}', name: "admin.property.delete", methods: ['POST', 'DELETE'])]
    // ici on a ajoiter la methods pour notifier qu'on accepte juste les requetes qui vienne de cette methode
    public function delete(Property $property, Request $request, $id){

        $property = $this->repository->find($id);
       
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){ // ici on recupere le csrf token, il prend en param l'id et le token, le token est recup grace a $request->get() qui prend en param le nom du champ ou on a defini le token
        
            $this->entityManager->remove($property);
            $this->entityManager->flush();
            $this->addFlash('success',  'Bien supprimer avec success');

        }
        
        return $this->redirectToRoute('admin.property.index');

    }
}
