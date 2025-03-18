<?php

namespace App\Entity;

use App\Repository\HeaderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HeaderRepository::class)]
#[Vich\Uploadable]
class Header
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $buttonTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $buttonLink = null;

    #[ORM\Column]
    private ?int $orderAppear = null;

    #[Vich\UploadableField(mapping: 'headers', fileNameProperty: 'imageName')]
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
    private ?string $sideImage = null;

    #[ORM\Column(length: 255)]
    private ?string $backgroundColor = null;

    #[ORM\ManyToOne(inversedBy: 'headers')]
    private ?Category $categoryProduct = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $paragraph = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getButtonTitle(): ?string
    {
        return $this->buttonTitle;
    }

    public function setButtonTitle(string $buttonTitle): static
    {
        $this->buttonTitle = $buttonTitle;

        return $this;
    }

    public function getButtonLink(): ?string
    {
        return $this->buttonLink;
    }

    public function setButtonLink(string $buttonLink): static
    {
        $this->buttonLink = $buttonLink;

        return $this;
    }

    public function getOrderAppear(): ?int
    {
        return $this->orderAppear;
    }

    public function setOrderAppear(int $orderAppear): static
    {
        $this->orderAppear = $orderAppear;

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

    public function getSideImage(): ?string
    {
        return $this->sideImage;
    }

    public function setSideImage(string $sideImage): static
    {
        $this->sideImage = $sideImage;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getCategoryProduct(): ?Category
    {
        return $this->categoryProduct;
    }

    public function setCategoryProduct(?Category $categoryProduct): static
    {
        $this->categoryProduct = $categoryProduct;

        return $this;
    }

    public function getParagraph(): ?string
    {
        return $this->paragraph;
    }

    public function setParagraph(?string $paragraph): static
    {
        $this->paragraph = $paragraph;

        return $this;
    }
}
