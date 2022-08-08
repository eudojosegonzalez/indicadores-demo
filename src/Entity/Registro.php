<?php

namespace App\Entity;

use App\Repository\RegistroRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistroRepository::class)
 */
class Registro
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
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity=Indicador::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $indicador;

    /**
     * @ORM\ManyToOne(targetEntity=Periodo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $periodo;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     */
    private $valor;

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

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getIndicador(): ?Indicador
    {
        return $this->indicador;
    }

    public function setIndicador(?Indicador $indicador): self
    {
        $this->indicador = $indicador;

        return $this;
    }

    public function getPeriodo(): ?Periodo
    {
        return $this->periodo;
    }

    public function setPeriodo(?Periodo $periodo): self
    {
        $this->periodo = $periodo;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}
