<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ticket:read","orders:read-all","order:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"ticket:read","ticket:write","orders:read-all","order:read"})
     * @NotBlank()
     */
    private $qte;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orders:read-all","order:read"})
     * @NotBlank()
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=Ticket::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orders:read-all","order:read"})
     */
    private $ticket;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"ticket:read","orders:read-all","order:read"})
     */
    
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"orders:read-all","order:read"})
     */
    private $updatedAt;


    public function __construct()
    {
        // $this->ticket = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

   
}
