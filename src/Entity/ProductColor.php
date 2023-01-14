<?php

namespace App\Entity;

use App\Repository\ProductColorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductColorRepository::class)]
class ProductColor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ProductImage $image = null;

    #[ORM\ManyToOne(inversedBy: 'color')]
    private ?ProductSize $productSize = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?ProductImage
    {
        return $this->image;
    }

    public function setImage(?ProductImage $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getProductSize(): ?ProductSize
    {
        return $this->productSize;
    }

    public function setProductSize(?ProductSize $productSize): self
    {
        $this->productSize = $productSize;

        return $this;
    }


    public function __toString(): string
    {
        return $this->name;
    }

}
