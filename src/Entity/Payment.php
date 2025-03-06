<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $creditCardNumber = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $expirationDate = null;

    #[ORM\Column(length: 10)]
    private ?string $cvv = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'payment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditCardNumber(): ?string
    {
        return $this->creditCardNumber;
    }

    public function setCreditCardNumber(string $creditCardNumber): static
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): self
{
    // Assuming expirationDate is in 'MM/YY' format
    $date = \DateTime::createFromFormat('m/y', $expirationDate);

    if ($date === false) {
        throw new \InvalidArgumentException("Invalid expiration date format. Use MM/YY.");
    }

    $this->expirationDate = $date;

    return $this;
}


    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

 
    public function getFormattedExpirationDate(): ?string
    {
        return $this->expirationDate ? $this->expirationDate->format('m/y') : null;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
