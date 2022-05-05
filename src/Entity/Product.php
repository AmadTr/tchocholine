<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("prods:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("prods:read")
     * @Assert\NotBlank(message="Le nom du produit doit etre indique.")
     * 
     * @Assert\Length(
     * min=3,
     * minMessage="plus de 3 caratères",
     * max=255,
     * maxMessage="moins de 255 caractères") 
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("prods:read")
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("prods:read")
     * @Assert\PositiveOrZero(message="La quantité doit etre >=0.")
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="product")
     */
    private $orderlines;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message="La quantité doit etre >=0.")
     * 
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=PhotosProduct::class, mappedBy="product")
     */
    private $photosProduct;

   

    public function __construct()
    {
        $this->orderlines = new ArrayCollection();
        $this->photosProduct = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, OrderLine>
     */
    public function getOrderlines(): Collection
    {
        return $this->orderlines;
    }

    public function addOrderline(OrderLine $orderline): self
    {
        if (!$this->orderlines->contains($orderline)) {
            $this->orderlines[] = $orderline;
            $orderline->setProduct($this);
        }

        return $this;
    }

    public function removeOrderline(OrderLine $orderline): self
    {
        if ($this->orderlines->removeElement($orderline)) {
            // set the owning side to null (unless already changed)
            if ($orderline->getProduct() === $this) {
                $orderline->setProduct(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, PhotosProduct>
     */
    public function getPhotosProduct(): Collection
    {
        return $this->photosProduct;
    }

    public function addPhotosProduct(PhotosProduct $photosProduct): self
    {
        if (!$this->photosProduct->contains($photosProduct)) {
            $this->photosProduct[] = $photosProduct;
            $photosProduct->setProduct($this);
        }

        return $this;
    }

    public function removePhotosProduct(PhotosProduct $photosProduct): self
    {
        if ($this->photosProduct->removeElement($photosProduct)) {
            // set the owning side to null (unless already changed)
            if ($photosProduct->getProduct() === $this) {
                $photosProduct->setProduct(null);
            }
        }

        return $this;
    }  
   
}
