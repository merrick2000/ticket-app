<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TicketRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tickets:read-all","ticket:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"tickets:read-all", "ticket:read","user:read"})
     * @NotBlank() 
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2) 
     * @Groups({"tickets:read-all", "ticket:read","user:read"})
     * @NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"tickets:read-all", "ticket:read","user:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"tickets:read-all","ticket:read","user:read"})
     */
    private $number;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"tickets:read-all","ticket:read"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="ticket")
     * @Groups({"ticket:read"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=MyTicketUser::class, inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $myTicketUser;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->number = 'TK_'. uniqid();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->imageUrl = $image_url;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
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

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addTicket($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeTicket($this);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getMyTicketUser(): ?MyTicketUser
    {
        return $this->myTicketUser;
    }

    public function setMyTicketUser(?MyTicketUser $myTicketUser): self
    {
        $this->myTicketUser = $myTicketUser;

        return $this;
    }
}
