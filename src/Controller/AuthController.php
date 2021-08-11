<?php

namespace App\Controller;

use App\Repository\MyTicketUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


class AuthController extends AbstractController
{
    // public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    // {
    //  return new JsonResponse(['token' => $JWTManager->create($user)]);
    // }
    /**
     * @Route("/api/login", name="_login", methods={"POST"})
     */
    public function login(Request $request, MyTicketUserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $jsonReceived = json_decode($request->getContent());
        try {
            if (!empty($jsonReceived->email) && !empty($jsonReceived->password)) {
                $userCheck = $userRepository->findOneBy([
                    'email' => $jsonReceived->email,
                ]);
                if (!is_null($userCheck)) {
                    
                    if ($passwordEncoder->isPasswordValid($userCheck, $jsonReceived->password)) {
                        return $this->json([
                            'token' => $userCheck->getToken(),
                            'code' => 200
                        ], 200, []);
                    }
                    return $this->json([
                        'message' => "Invalid user credentials",
                        'code' => 200
                    ], 200, []);
                    
                }
                return $this->json([
                    'message' => 'Email not found.',
                    'code' => 400
                ], 409, []);
            }
            return $this->json([
                "code" => 400,
                "message" => "Need both email and password for login. Missing one of them."
            ], 400);
        } catch (NotEncodableValueException
            $e) {
            return $this->json([
                "code" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
