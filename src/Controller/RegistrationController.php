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
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/api", name="api_")
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
     * @return \FOS\RestBundle\View\View
     */
    public function index(Request $request)
    {
        $jsonReceived = $request->getContent();
        $email = $request->get('email');
        $password = $request->get('password');

        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (!is_null($user)) {
            return $this->view([
                'message' => 'User already exists'
            ], Response::HTTP_CONFLICT);
        }

        $user = new MyTicketUser();

        $user->setEmail($email);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $password)
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->view($user, Response::HTTP_CREATED)->setContext((new Context())->setGroups(['user:read']));
    }
}
