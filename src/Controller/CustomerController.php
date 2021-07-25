<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
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
class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="all-customers", methods={"GET"})
     */
    public function index(CustomerRepository $customerRepo): Response
    {
        return $this->json($customerRepo->findAll(), 200, [], ['groups' => 'customers:read-all']);
    }

    /**
     * @Route("/customers/{id}", name="get-customer-by-id", methods={"GET"})
     */
    public function getTicket(Customer $id=null, CustomerRepository $customerRepo)
    {
        if($id){
            return $this->json($customerRepo->find($id), 200, [], ['groups' => 'customer:read']);
        }
        return $this->json([
            'message' => 'Unable to find this customer.',
            'code' => 404
        ], 404);
    }

    /**
     * @Route("/customers", name="new-customer", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, CustomerRepository $customerRepo)
    {
        $jsonReceived = $request->getContent();
        
        try{
            $user = json_decode($jsonReceived);
            //$user = $userRepo->find($user);
            // if(!$user){
            //     return $this->json([
            //         "message" => "User not found",
            //         "code" =>400
            //     ], 400);
            // }

            $customer = $serializer->deserialize($jsonReceived, Customer::class, 'json');
            // $ticket->setPrice((string)$ticket->getPrice());
            // $ticket->setUser($user);
            $errors = $validator->validate($customer);
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
            $em->persist($customer);
            $em->flush();

            return $this->json($customer, 201, [], ['groups' => 'customer:read']);
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
    * @Route("/customers/{id}", name="update-customer", methods={"PUT"})
    */
    public function update(Customer $id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $updateCustomer = $request->getContent();
        if ($id)
        {

        try{

            $updateCustomer = $serializer->deserialize($updateCustomer, Customer::class, 'json');
            $errors = $validator->validate($updateCustomer);
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
            // $date = new \DateTime();
            $id->setFullname($updateCustomer->getFullname());
            $id->setEmail($updateCustomer->getEmail());
            // $id->setUpdatedAt($updateCustomer->getUpdatedAt());
        
            $em->persist($id);
            $em->flush();

            return $this->json($id, 201, [], ['groups' => 'customer:read']);
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
            'message' => 'Undefined customer',
            'code' => 404
        ], 404);
    }

    /**  
    * @Route("/customers/{id}", name="delete-customer", methods={"DELETE"})
    */
    public function delete(Customer $id=null, EntityManagerInterface $em)
    {

        if (!$id)
        {
            return $this->json([ 
                'message' => 'Unable to find this customer.',
                'code' => 404
            ], 404);
        }
        $em->remove($id);
        $em->flush();
            
        return $this->json([
            'message' => 'Customer deleted with success.',
            'code' => 200
        ], 200);
        
    } 
}

