<?php
namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PropertyController extends AbstractController {

    private $repository;
    private $entityManager;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $entityManager)
    {
        // $repository = $entityManager->getRepository(Property::class);
        // dump($repository);

        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    #[Route('/biens', name: "property.index")]

    public function index(PaginatorInterface $paginator, Request $request ): Response {

        $search = new PropertySearch();
        
        $form = $this->createForm(PropertySearchType::class, $search);

        $form->handleRequest($request);
        
        // if ($form->isSubmitted() && $form->isValid()) { 
            
        // }
        

        $properties = $paginator->paginate(  //il prend en param la requete , la page courante et la limite 
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );


        // $property = new Property();

        // $property->setTitle('Mon deuxieme bien')
        //          ->setPrice(300000)
        //          ->setRooms(5)
        //          ->setBedrooms(4)
        //          ->setDescription('Une petite description')
        //          ->setSurface(70)
        //          ->setFloor(5)
        //          ->setHeat(0)
        //          ->setCity('Ivry-Sur-Sein')
        //          ->setAdress('80 Avenu de verdun')
        //          ->setPostalCode('94200');
        
        // $this->entityManager->persist($property);

        // $this->entityManager->flush();

        return $this->render('property/index.html.twig',[
            
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    #[Route('/biens/{slug}/{id}', name: "property.show", requirements: ["slug" => "[a-z0-9\-]*"])] 

    public function show($id, string $slug ): Response
    {
        $property = $this->repository->find($id);    

        // dump($property->getId());
           // comunique avec la bd pour find la propery avec l'id passer en param
        // au lieu d'utiliser le repository->find() on a injecter directement l'entity Property et ensuite il detectera au niveau de la route le id 
        // et fera automatiquement le repository->find() a notre place

        if($property->getSlug() != $slug ){

            return $this->redirectToRoute('property.show', [  // petite remarque , on fait un return sur le redirectToRoute car elle renvoie un object de type Response
                'id' => $property->getId(),
                'slug' => $property->getSlug(),
                301
            ]);
        }

        return $this->render('property/show.html.twig',[
            'current_menu' => 'properties',
            'property' => $property
        ]);
    }
}