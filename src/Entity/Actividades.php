<?php

namespace App\Entity;

use App\Repository\ActividadesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActividadesRepository::class)
 */
class Actividades
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
     * @ORM\Column(type="time")
     */
    private $h_inicio;

    /**
     * @ORM\Column(type="time")
     */
    private $h_fin;

    /**
     * @ORM\OneToMany(targetEntity=Sala::class, mappedBy="id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sala;

    /**
     * @ORM\Column(type="string")
     */
    private $imagen;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }


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

    public function getHInicio(): ?\DateTimeInterface
    {
        return $this->h_inicio;
    }

    public function setHInicio(\DateTimeInterface $h_inicio): self
    {
        $this->h_inicio = $h_inicio;

        return $this;
    }

    public function getHFin(): ?\DateTimeInterface
    {
        return $this->h_fin;
    }

    public function setHFin(\DateTimeInterface $h_fin): self
    {
        $this->h_fin = $h_fin;

        return $this;
    }

    public function getSala(): ?Sala
    {
        return $this->sala;
    }

    public function setSala(Sala $sala): self
    {
        $this->sala = $sala;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

}
