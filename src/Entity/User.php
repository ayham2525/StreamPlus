<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 25)]
    private ?string $subscriptionType = null;


    #[ORM\OneToOne(targetEntity: Address::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Address $address = null;

    #[ORM\OneToOne(targetEntity: Payment::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    private $roles = [];

    // This method will return the user roles
    public function getRoles(): array
    {
        // Ensure that users have ROLE_USER and possibly ROLE_PREMIUM
        $roles = $this->roles;
        if ($this->getSubscriptionType() === 'premium') {
            $roles[] = 'ROLE_PREMIUM'; // Add a custom role for premium users
        }
        return array_unique($roles);
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getSubscriptionType(): ?string
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(string $subscriptionType): static
    {
        $this->subscriptionType = $subscriptionType;

        return $this;
    }
    public function getAddress(): ?Address
    {
        return $this->address;
    }
    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }
    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
