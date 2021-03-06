<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MyTicketUserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=MyTicketUserRepository::class)
 */
class MyTicketUser implements UserInterface
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
     * @NotBlank()
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Groups({"users:read","user:read","user:write"})
     * @NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"users:read","user:read","user:write"})
     * @NotBlank()
     */
    private $telephone;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"users:read","user:read"})
     */
    private $createdAt;

    /**
<<<<<<< HEAD
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="myTicketUser")
=======
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"user:write"})
>>>>>>> 3426385de8c7bb373c03c7e2c0cc83f25ea87f43
     */
    private $tickets;


    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @NotBlank()
     */
    private $password;

    /**
     * @Groups({"user:read"})
     */
    private $ticketCount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tickets = new ArrayCollection();
        $this->ticketCount = 0;
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
            $ticket->setMyTicketUser($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getMyTicketUser() === $this) {
                $ticket->setMyTicketUser(null);
            }
        }

        return $this;
    }

    public function setTicketCount(): void
    {
        $this->ticketCount = count($this->tickets);
    }

    public function getTicketCount(): int
    {
        return  $this->ticketCount = count($this->tickets);
    }

    /**
    * A visual identifier that represents this user.
    *
    * @see UserInterface
    */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
