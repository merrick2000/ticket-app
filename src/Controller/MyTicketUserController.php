<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyTicketUserController extends AbstractController
{
    #[Route('/my/ticket/user', name: 'my_ticket_user')]
    public function index(): Response
    {
        return $this->render('my_ticket_user/index.html.twig', [
            'controller_name' => 'MyTicketUserController',
        ]);
    }
}
