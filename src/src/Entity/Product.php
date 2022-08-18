<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Detail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Price = null;

    #[ORM\Column(length: 5)]
    private ?string $CategoryId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->Detail;
    }

    public function setDetail(?string $Detail): self
    {
        $this->Detail = $Detail;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(?string $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getCategoryId(): ?string
    {
        return $this->CategoryId;
    }

    public function setCategoryId(string $CategoryId): self
    {
        $this->CategoryId = $CategoryId;

        return $this;
    }
}
