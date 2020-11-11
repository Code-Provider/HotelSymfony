<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomLocataire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $PrenomLocataire;

    /**
     * @ORM\ManyToOne(targetEntity=Chambre::class, inversedBy="locations")
     * @ORM\OrderBy({"Num" = "ASC"})
     */
    private $Chambre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $DateDeb;

    /**
     * @ORM\Column(type="date")
     */
    private $DateFin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLocataire(): ?string
    {
        return $this->nomLocataire;
    }

    public function setNomLocataire(string $nomLocataire): self
    {
        $this->nomLocataire = $nomLocataire;

        return $this;
    }

    public function getPrenomLocataire(): ?string
    {
        return $this->PrenomLocataire;
    }

    public function setPrenomLocataire(string $PrenomLocataire): self
    {
        $this->PrenomLocataire = $PrenomLocataire;

        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->Chambre;
    }

    public function setChambre(?Chambre $Chambre): self
    {
        $this->Chambre = $Chambre;

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

    public function getDateDeb(): ?\DateTimeInterface
    {
        return $this->DateDeb;
    }

    public function setDateDeb(\DateTimeInterface $DateDeb): self
    {
        $this->DateDeb = $DateDeb;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }
}
