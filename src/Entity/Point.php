<?php

namespace App\Entity;

use App\Repository\PointRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PointRepository::class)
 */
class Point
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="points")
     */
    private $id_user;

    /**
     * @ORM\Column(type="json")
     */
    private $point = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIdUser(): ?user
    {
        return $this->id_user;
    }

    public function setIdUser(?user $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getPoint(): ?array
    {
        return $this->point;
    }

    public function setPoint(array $point): self
    {
        $this->point = $point;

        return $this;
    }
}
