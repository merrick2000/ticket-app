<?php

namespace App\Controller;

use App\Entity\MyTicketUser;
use FOS\RestBundle\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MyTicketUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Serializer\SerializerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class RegistrationController extends AbstractFOSRestController
{
    /**
     * @var MyTicketUserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(MyTicketUserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     * @param Request $request
     */
    public function index(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonReceived = $request->getContent();

        try {

            //var_dump($jsonReceived->email);exit;

            $user = $serializer->deserialize($jsonReceived, MyTicketUser::class, 'json');
            //$user = new MyTicketUser();
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                //Formatting  errors
                for ($i = 0; $i < count($errors); $i++) {
                    $error[$errors->get($i)->getPropertyPath()] = $errors->get($i)->getMessage();
                }
                return $this->json([
                    "message" => $error,
                    "code" => 400
                ], 400);
            }

            $email = json_decode($jsonReceived)->email;
            $password = json_decode($jsonReceived)->password;
            $token = "_TK" . sha1(uniqid());

            $userCheck = $this->userRepository->findOneBy([
                'email' => $email,
            ]);

            if (!is_null($userCheck)) {
                return $this->json([
                    'message' => 'User already exists',
                    'code' => 409
                ], 409, []);
            }
            $user->setEmail($email);
            $user->setToken($token);
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            //return $this->json($user, 201, [], ['groups' => 'user:read']);
            return $this->json([
                "message" => "User created successfully.",
                "token" => $token
            ], 201);
        } catch (NotEncodableValueException
            $e) {
            return $this->json([
                "code" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
