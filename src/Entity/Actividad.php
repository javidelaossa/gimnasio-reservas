<?php

namespace App\Entity;

use App\Repository\ActividadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActividadRepository::class)
 */
class Actividad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $hinic;

    /**
     * @ORM\Column(type="time")
     */
    private $hfin;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $monitor;

    /**
     * @ORM\ManyToOne(targetEntity=Sala::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $sala;

    /**
     * @ORM\ManyToOne(targetEntity=TipoAct::class)
     * @ORM\JoinColumn({
     * @ORM\JoinColumn(name="nombre_id", referencedColumnName="id")
     *     })
     */
    private $nombre;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHinic(): ?\DateTimeInterface
    {
        return $this->hinic;
    }

    public function setHinic(\DateTimeInterface $hinic): self
    {
        $this->hinic = $hinic;

        return $this;
    }

    public function getHfin(): ?\DateTimeInterface
    {
        return $this->hfin;
    }

    public function setHfin(\DateTimeInterface $hfin): self
    {
        $this->hfin = $hfin;

        return $this;
    }

    public function getMonitor(): ?Usuario
    {
        return $this->monitor;
    }

    public function setMonitor(?Usuario $monitor): self
    {
        $this->monitor = $monitor;

        return $this;
    }

    public function getSala(): ?Sala
    {
        return $this->sala;
    }

    public function setSala(?Sala $sala): self
    {
        $this->sala = $sala;

        return $this;
    }

    public function getNombre(): ?TipoAct
    {
        return $this->nombre;
    }

    public function setNombre(?TipoAct $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

}
