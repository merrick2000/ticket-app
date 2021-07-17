<?php

namespace App\Controller;

use App\Repository\MyTicketUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RegistrationController extends AbstractController
{
    private $userRepository;
    public function __construct(MyTicketUserRepository  $userRepo)
    {
        $this->userRepository = $userRepo;   
    }
    /**
     * @Route("/register", name="registration")
     */
    public function createUser(Request $request): Response
    {
       
    }
}
