<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservaRepository::class)
 */
class Reserva
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Actividad::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $actividad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $asistencia;

    /**
     * @ORM\Column(type="date")
     */
    private $dia;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getActividad(): ?Actividad
    {
        return $this->actividad;
    }

    public function setActividad(?Actividad $actividad): self
    {
        $this->actividad = $actividad;

        return $this;
    }

    public function getAsistencia(): ?bool
    {
        return $this->asistencia;
    }

    public function setAsistencia(bool $asistencia): self
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    public function getDia(): ?\DateTimeInterface
    {
        return $this->dia;
    }

    public function setDia(\DateTimeInterface $dia): self
    {
        $this->dia = $dia;

        return $this;
    }
}
