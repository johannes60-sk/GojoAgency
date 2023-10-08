<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPictureController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(PropertyRepository $repository ,EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }


    #[Route('/admin/delete/{id}', name: 'admin.picture.delete',  methods: ['POST', 'DELETE'])]
    public function delete(Picture $picture,  Request $request){

        $csrfToken = $request->request->get('csrf_token');

        if ($this->isCsrfTokenValid('delete'. $picture->getId(),  $csrfToken)) {
            $this->entityManager->remove($picture);
            $this->entityManager->flush();

        }else{
            return new JsonResponse(['success' => 1]);
        }

        // return $this->redirectToRoute('admin.property.edit', ['id' => $propertyId ]);
        return new JsonResponse(['error' => 'Token invalid'], 400);
    }
    
}