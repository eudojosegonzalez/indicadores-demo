<?php

namespace App\Entity;

use App\Repository\SubreporteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubreporteRepository::class)
 */
class Subreporte
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
     * @ORM\ManyToOne(targetEntity=Reporte::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $reporte;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $procedimiento;

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

    public function getReporte(): ?Reporte
    {
        return $this->reporte;
    }

    public function setReporte(?Reporte $reporte): self
    {
        $this->reporte = $reporte;

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

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getProcedimiento(): ?string
    {
        return $this->procedimiento;
    }

    public function setProcedimiento(string $procedimiento): self
    {
        $this->procedimiento = $procedimiento;

        return $this;
    }
}
