<?php

namespace App\Entity;

use App\Repository\PropertySearchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: PropertySearchRepository::class)]
class PropertySearch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $maxPrice = null;

    #[ORM\Column]
    #[Assert\Range(min: 10, max: 400)]
    private ?int $minSurface = null;
    private ?ArrayCollection $options; 

    public function __construct(){

        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice): static
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getMinSurface(): ?int
    {
        return $this->minSurface;
    }

    public function setMinSurface(int $minSurface): static
    {
        $this->minSurface = $minSurface;

        return $this;
    }

    public function getOptions(): ArrayCollection
    {

        return $this->options;
    }

    public function setOptions(ArrayCollection $options):void
    {
        $this->options = $options;
    }
}
