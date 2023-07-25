<?php

namespace App\Entity;

use App\Repository\AufgabeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AufgabeRepository::class)]
class Aufgabe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Datum = null;

    #[ORM\Column(length: 255)]
    private ?string $Aufgabe = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $bildName = null;

    #[ORM\Column]
    private int $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }

    public function getDatum(): ?string
    {
        return $this->Datum;
    }

    public function setDatum(string $Datum): static
    {
        $this->Datum = $Datum;

        return $this;
    }

    public function getAufgabe(): ?string
    {
        return $this->Aufgabe;
    }

    public function setAufgabe(string $Aufgabe): static
    {
        $this->Aufgabe = $Aufgabe;

        return $this;
    }

    public function getBildName(): ?string
    {
        return $this->bildName;
    }

    public function setBildName(?string $bildName): self
    {
        $this->bildName = $bildName;

        return $this;
    }
}
