<?php

namespace App\Entity;

use App\Repository\ReservasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservasRepository::class)
 */
class Reservas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Usuario::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\OneToOne(targetEntity=Actividades::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $actividad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $asistencia;

    public function getId(): ?int
    {
        return $this->id;
    }

        public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getActividad(): ?Actividades
    {
        return $this->actividad;
    }

    public function setActividad(Actividades $actividad): self
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
}
