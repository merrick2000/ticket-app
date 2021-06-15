<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Orders;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

/**
 * @Route("/api")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/orders", name="all-orders", methods={"GET"})
     */
    public function index(OrderRepository $orderRepo): Response
    {
        return $this->json($orderRepo->findAll(), 200, [], ['groups' => 'orders:read-all']);
    }

    /**
     * @Route("/orders/{id}", name="get-order-by-id", methods={"GET"})
     */
    public function getTicket(Order $id=null, OrderRepository $orderRepo)
    {
        if($id){
            return $this->json($orderRepo->find($id), 200, [], ['groups' => 'order:read']);
        }
        return $this->json([
            'message' => 'Unable to find this order.',
            'code' => 404
        ], 404);
    }

    /**
     * @Route("/orders", name="new-order", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, TicketRepository $ticketRepository, CustomerRepository $customerRepository)
    {
        $jsonReceived = $request->getContent();
        // if(!is_array(json_decode($jsonReceived)->ticket)){
        //     return $this->json([
        //         "message" => "ticket must be array, ex: ticket: [2]",
        //         "code" =>400
        //     ], 400);
        // }

        try{

            
            $ticket = json_decode($jsonReceived)->ticket;
            $customer = json_decode($jsonReceived)->customer;
            $ticket = $ticketRepository->find($ticket);
            $customer = $customerRepository->find($customer);
           // dd($ticket);
            if(!($ticket && $customer)){
                return $this->json([
                    "message" => "Customer or ticket not found",
                    "code" =>400
                ], 400);
            }
            
            $order = $serializer->deserialize($jsonReceived, Order::class, 'json');
            //dd($order);
            $order->setTicket($ticket)
                  ->setCustomer($customer);
            
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
            
            //dd($ticket);
            $em->persist($order);
            $em->flush();

            return $this->json($order, 201, [], ['groups' => 'order:read']);
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

    /**
    * @Route("/orders/{id}", name="update-order", methods={"PUT"})
    */
    public function update(Order $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $updateOrder = $request->getContent();
        if ($id)
        {

        try{

            $updateOrder = $serializer->deserialize($updateOrder, Order::class, 'json');
            $errors = $validator->validate($updateOrder);
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
            $date = new \DateTime();
            $id->setUpdatedAt($date);
            $id->setQte($updateOrder->getQte());
            $id->setTicket($updateOrder->getTicket());
        
            $em->persist($id);
            $em->flush();

            return $this->json($id, 201, [], ['groups' => 'order:read']);
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

        return $this->json([
            'message' => 'Undefined held',
            'code' => 404
        ], 404);
    }

    /**
    * @Route("/orders/{id}", name="delete-order", methods={"DELETE"})
    */
    public function delete(Order $id=null, EntityManagerInterface $em)
    {

        if (!$id)
        {
            return $this->json([
                'message' => 'Unable to find this order.',
                'code' => 404
            ], 404);
        }
        $em->remove($id);
        $em->flush();
            
        return $this->json([
            'message' => 'Order deleted with success.',
            'code' => 200
        ], 200);
        
    } 
}
