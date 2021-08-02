<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
     return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}
