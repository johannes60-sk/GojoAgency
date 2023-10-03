<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {

    #[Route('/', name: "home")]
    public function index (PropertyRepository $repository): Response {

        $properties = $repository->findLatest();

        return $this->render('/pages/home.html.twig',[
            'properties' => $properties
        ]);

        // return $this->render('/pages/home.html.twig');
    }
} 

// Reste du code du fichier
