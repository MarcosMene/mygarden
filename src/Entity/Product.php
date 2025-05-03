<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity('name')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName')]
    #[Assert\File(
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        mimeTypesMessage: 'Invalid file type! Please upload a JPG, JPEG, PNG or WEBP image.'
    )]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;


    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $tva = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSuggestion = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?ColorProduct $colorProduct = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $promotion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBestSeller = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPromotion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'product')]
    private Collection $reviews;

    public function __construct()
    {
        // AUTOMATICALLY SET THE CREATEDAT FIELD TO THE CURRENT DATE AND TIME
        $this->createdAt = new \DateTime();
        $this->reviews = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // IT IS REQUIRED THAT AT LEAST ONE FIELD CHANGES IF YOU ARE USING DOCTRINE
            // OTHERWISE THE EVENT LISTENERS WON'T BE CALLED AND THE FILE IS LOST
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = ($description);

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceWt()
    {
        if ($this->price === null || $this->tva === null) {
            return 0;
        }

        $coeff = 1 + ($this->tva / 100);
        return round((float) $this->price * $coeff, 2);
    }

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(int $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function isSuggestion(): ?bool
    {
        return $this->isSuggestion;
    }

    public function setIsSuggestion(?bool $isSuggestion): static
    {
        $this->isSuggestion = $isSuggestion;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getColorProduct(): ?ColorProduct
    {
        return $this->colorProduct;
    }

    public function setColorProduct(?ColorProduct $colorProduct): static
    {
        $this->colorProduct = $colorProduct;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getPromotion(): ?int
    {
        return $this->promotion;
    }

    public function setPromotion(?int $promotion): static
    {
        $this->promotion = $promotion;
        return $this;
    }

    public function isBestSeller(): ?bool
    {
        return $this->isBestSeller;
    }

    public function setBestSeller(?bool $isBestSeller): static
    {
        $this->isBestSeller = $isBestSeller;

        return $this;
    }

    public function isPromotion(): ?bool
    {
        return $this->isPromotion;
    }

    public function setIsPromotion(?bool $isPromotion): static
    {
        $this->isPromotion = $isPromotion;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // SET THE OWNING SIDE TO NULL (UNLESS ALREADY CHANGED)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    //CALCULATE THE AVERAGE RATING FOR STARS OF PRODUCTS
    public function getAverageRating(): float
    {
        $totalReviews = count($this->reviews);
        if ($totalReviews === 0) {
            return 0;
        }

        $totalPoints = array_reduce($this->reviews->toArray(), function ($carry, $review) {
            return $carry + $review->getRate();
        }, 0);

        return $totalPoints / $totalReviews;
    }
}