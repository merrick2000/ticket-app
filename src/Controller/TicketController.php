<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\MyTicketUser;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MyTicketUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class TicketController extends AbstractController
{
    /**
     * @Route("/tickets", name="all-tickets", methods={"GET"})
     */
    public function index(TicketRepository $ticketRepo): Response
    {
        return $this->json($ticketRepo->findAll(), 200, [], ['groups' => 'tickets:read-all']);
        // return $this->render('ticket/index.html.twig', [
        //     'controller_name' => 'TicketController',
        // ]);
    }

    /**
     * @Route("/tickets/{id}", name="get-ticket-by-id", methods={"GET"})
     */
    public function getTicket(Ticket $id=null, TicketRepository $ticketRepo)
    {
        if($id){
            return $this->json($ticketRepo->find($id), 200, [], ['groups' => 'ticket:read']);
        }
        return $this->json([
            'message' => 'Unable to find this ticket.',
            'code' => 404
        ], 404);
    }

    /**
     * @Route("/tickets", name="new-ticket", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, MyTicketUserRepository $userRepo)
    {
        $jsonReceived = $request->getContent();

        try{
            
            $user = json_decode($jsonReceived)->user;
            $user = $userRepo->find($user);
            $ticket = $serializer->deserialize($jsonReceived, Ticket::class, 'json');
            $ticket->setPrice((string)$ticket->getPrice());
            
            var_dump($user);
            
            $errors = $validator->validate($ticket);
            if (count($errors) > 0)
            {
                //Formatting  errors
                for ($i=0; $i < count($errors); $i++) 
                { 
                    $error[$errors->get($i)->getPropertyPath()] = $errors->get($i)->getMessage();
                }
                return $this->json([
                    "message" =>$error,
                    "code" =>400
                ], 400);
            }
            
            $em->persist($ticket);
            $em->flush();

            return $this->json($ticket, 201, [], ['groups' => 'ticket:read']);
        }
        catch(NotEncodableValueException
 $e)
        {
            return $this->json([
                "code" => 400,
                "message" => $e->getMessage()
            ], 400);

        }
    }
}
