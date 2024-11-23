<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails')]
    private ?Order $myOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column(length: 255)]
    private ?string $productIllustration = null;

    #[ORM\Column]
    private ?int $productQuantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $productPrice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $productTva = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $productPromotion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyOrder(): ?Order
    {
        return $this->myOrder;
    }

    public function setMyOrder(?Order $myOrder): static
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductIllustration(): ?string
    {
        return $this->productIllustration;
    }

    public function setProductIllustration(string $productIllustration): static
    {
        $this->productIllustration = $productIllustration;

        return $this;
    }

//price with tax and promotion
    public function getProductPriceWt(){
        $coeff = 1+ ($this->productTva/100);
        return $coeff *$this->productPrice*((100 - $this->productPromotion )/100);
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): static
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getProductPrice(): ?string
    {
        return $this->productPrice;
    }

    public function setProductPrice(string $productPrice): static
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductTva(): ?string
    {
        return $this->productTva;
    }

    public function setProductTva(string $productTva): static
    {
        $this->productTva = $productTva;

        return $this;
    }

    public function getProductPromotion(): ?string
    {
        return $this->productPromotion;
    }

    public function setProductPromotion(string $productPromotion): static
    {
        $this->productPromotion = $productPromotion;

        return $this;
    }
}
