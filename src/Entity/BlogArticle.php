<?php

namespace App\Entity;

use App\Repository\BlogArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: BlogArticleRepository::class)]

#[Vich\Uploadable]
#[UniqueEntity('title')]

class BlogArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column]
    private ?bool $isValidated = false;

    #[ORM\ManyToOne(inversedBy: 'blogArticles', cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotBlank(message: 'Select one category')]
    private ?BlogCategory $category = null;

    /**
     * @var Collection<int, BlogTag>
     */
    #[ORM\ManyToMany(targetEntity: BlogTag::class, cascade: ["persist"])]
    #[Assert\NotBlank(message: 'Select at least one tag')]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'blogArticles')]
    private ?User $author = null;

    //AUTHOR IMAGE
    #[Vich\UploadableField(mapping: 'user_images', fileNameProperty: 'authorImageName')]
    #[Assert\File(
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        mimeTypesMessage: 'Invalid file type! Please upload a JPG, JPEG, PNG or WEBP image.'
    )]
    private ?File $authorImageFile  = null;

    #[ORM\Column(nullable: true)]
    private ?string $authorImageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $authorImageUpdatedAt = null;

    //BLOG IMAGE
    #[Vich\UploadableField(mapping: 'blog_images', fileNameProperty: 'articleImageName')]
    #[Assert\File(
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        mimeTypesMessage: 'Invalid file type! Please upload a JPG, JPEG, PNG or WEBP image.'
    )]
    private ?File $articleImageFile  = null;

    #[ORM\Column(nullable: true)]
    private ?string $articleImageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $articleImageUpdatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, options: ["default" => "draft"])]
    private ?string $status = "draft";

    /**
     * @var Collection<int, BlogComment>
     */
    #[ORM\OneToMany(targetEntity: BlogComment::class, mappedBy: 'article', orphanRemoval: true, cascade: ["remove"])]
    private Collection $blogComments;

    //BLOG IMAGE
    public function setArticleImageFile(?File $imageArticleFile = null): void
    {
        $this->articleImageFile = $imageArticleFile;

        if (null !== $imageArticleFile) {
            $this->articleImageUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getArticleImageFile(): ?File
    {
        return $this->articleImageFile;
    }

    public function setArticleImageName(?string $articleImageName): void
    {
        $this->articleImageName = $articleImageName;
    }

    public function getArticleImageName(): ?string
    {
        return $this->articleImageName;
    }

    /**
     * Get the value of updatedAt
     */
    public function getArticleImageUpdatedAt()
    {
        return $this->articleImageUpdatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */
    public function setArticleImageUpdatedAt($articleImageUpdatedAt)
    {
        $this->articleImageUpdatedAt = $articleImageUpdatedAt;
        return $this;
    }

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->blogComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    //IMAGE AUTHOR
    public function setAuthorImageFile(?File $imageAuthorFile = null): void
    {
        $this->authorImageFile = $imageAuthorFile;

        if (null !== $imageAuthorFile) {
            $this->authorImageUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getAuthorImageFile(): ?File
    {
        return $this->authorImageFile;
    }

    public function setAuthorImageName(?string $authorImageName): void
    {
        $this->authorImageName = $authorImageName;
    }

    public function getAuthorImageName(): ?string
    {
        return $this->authorImageName;
    }

    /**
     * Get the value of updatedAt Author
     */
    public function getAuthorImageUpdatedAt()
    {
        return $this->authorImageUpdatedAt;
    }

    /**
     * Set the value of updatedAt Author
     *
     * @return  self
     */
    public function setAuthorImageUpdatedAt($authorImageUpdatedAt)
    {
        $this->authorImageUpdatedAt = $authorImageUpdatedAt;
        return $this;
    }


    public function getArticleImagePath(): ?string
    {
        return $this->articleImageName ? '/upload/blog/articles/' . $this->articleImageName : null;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = ucfirst(strtolower($title));

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }

    public function setValidated(bool $isValidated): static
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getCategory(): ?BlogCategory
    {
        return $this->category;
    }

    public function setCategory(?BlogCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, BlogTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(BlogTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(BlogTag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    // CHECK IF IT'S PUBLISHED
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * @return Collection<int, BlogComment>
     */
    public function getBlogComments(): Collection
    {
        return $this->blogComments;
    }

    public function addBlogComment(BlogComment $blogComment): static
    {
        if (!$this->blogComments->contains($blogComment)) {
            $this->blogComments->add($blogComment);
            $blogComment->setArticle($this);
        }

        return $this;
    }

    public function removeBlogComment(BlogComment $blogComment): static
    {
        if ($this->blogComments->removeElement($blogComment)) {
            // SET THE OWNING SIDE TO NULL (UNLESS ALREADY CHANGED)
            if ($blogComment->getArticle() === $this) {
                $blogComment->setArticle(null);
            }
        }

        return $this;
    }
}
