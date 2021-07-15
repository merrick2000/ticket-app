<?php

namespace App\Controller;

use App\Repository\MyTicketUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class MyTicketUserController extends AbstractController
{
   /**
     * @Route("/users", name="all-users", methods={"GET"})
     */
    public function index(MyTicketUserRepository $userRepo): Response
    {
        return $this->json($userRepo->findAll(), 200, [], ['groups' => 'user:read']);
    }


}
