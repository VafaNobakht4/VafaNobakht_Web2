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

    #[ORM\Column(length: 100)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $Price = null;

    #[ORM\Column(length: 5)]
    private ?string $Category = null;

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->Detail;
    }

    /**
     * @param string|null $Detail
     */
    public function setDetail(?string $Detail): void
    {
        $this->Detail = $Detail;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->Category;
    }

    /**
     * @param string|null $Category
     */
    public function setCategory(?string $Category): void
    {
        $this->Category = $Category;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

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

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(?string $Price): self
    {
        $this->Price = $Price;

        return $this;
    }
}
