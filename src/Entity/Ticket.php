<?php

namespace App\Entity;

use App\Entity\MyTicketUser;
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
     * @Groups({"tickets:read-all","ticket:read","order:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"tickets:read-all", "ticket:read","user:read","order:read"})
     * @NotBlank()
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Groups({"tickets:read-all", "ticket:read","user:read","orders:read-all","order:read"})
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
     * @Groups({"tickets:read-all","ticket:read","user:read","order:read"})
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"tickets:read-all", "ticket:read"})
     * @NotBlank()
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity=MyTicketUser::class, inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"tickets:read-all","ticket:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"tickets:read-all", "ticket:read"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="ticket", orphanRemoval=true)
     */
    private $orders;

    /**
     * @Groups({"tickets:read-all", "ticket:read"})
     */
    private $orderCount;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->number = 'TK_'. uniqid();
        $this->orders = new ArrayCollection();
        $this->orderCount = 0;
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

    public function getUser(): ?MyTicketUser
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

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

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
            $order->setTicket($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getTicket() === $this) {
                $order->setTicket(null);
            }
        }

        return $this;
    }

    public function setOrderCount(): void
    {
        $this->orderCount = count($this->orders);
    }

    public function getOrderCount(): int
    {
        return  $this->orderCount = count($this->orders);
    }
}
