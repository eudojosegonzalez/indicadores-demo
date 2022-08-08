<?php

namespace App\Entity;

use App\Repository\FormulaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormulaRepository::class)
 */
class Formula
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
    private $nombre;

    /**
     * @ORM\Column(type="text")
     */
    private $definicion;

    /**
     * @ORM\Column(type="text")
     */
    private $formula;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDefinicion(): ?string
    {
        return $this->definicion;
    }

    public function setDefinicion(string $definicion): self
    {
        $this->definicion = $definicion;

        return $this;
    }

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function setFormula(string $formula): self
    {
        $this->formula = $formula;

        return $this;
    }
}
