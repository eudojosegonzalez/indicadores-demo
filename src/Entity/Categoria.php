<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriaRepository::class)
 */
class Categoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ente::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnte(): ?Ente
    {
        return $this->ente;
    }

    public function setEnte(?Ente $ente): self
    {
        $this->ente = $ente;

        return $this;
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
    // Registra el método mágico para imprimir el nombre del estado, por ejemplo, California
    public function __toString()
    {
        return $this->nombre;
    }
}
