<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MyTicketUserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MyTicketUserRepository::class)
 */
class MyTicketUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tickets:read-all","ticket:read","user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tickets:read-all","ticket:read","user:read","user:write"})
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read","user:read","user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"users:read","user:read","user:write"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"users:read","user:read"})
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"user:read","user:write"})
     */
    private $tickets;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tickets = new ArrayCollection();
    }

    // public function __toString()
    // {
    //     return $this->fullname;
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setUser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getUser() === $this) {
                $ticket->setUser(null);
            }
        }

        return $this;
    }
}
