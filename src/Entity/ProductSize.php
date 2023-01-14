<?php

namespace App\Entity;

use App\Repository\ProductSizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductSizeRepository::class)]
class ProductSize
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'sizes')]
    private ?Products $products = null;

    #[ORM\OneToMany(mappedBy: 'productSize', targetEntity: ProductColor::class)]
    private Collection $color;

    public function __construct()
    {
        $this->color = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return Collection<int, ProductColor>
     */
    public function getColor(): Collection
    {
        return $this->color;
    }

    public function addColor(ProductColor $color): self
    {
        if (!$this->color->contains($color)) {
            $this->color->add($color);
            $color->setProductSize($this);
        }

        return $this;
    }

    public function removeColor(ProductColor $color): self
    {
        if ($this->color->removeElement($color)) {
            // set the owning side to null (unless already changed)
            if ($color->getProductSize() === $this) {
                $color->setProductSize(null);
            }
        }

        return $this;
    }
}
