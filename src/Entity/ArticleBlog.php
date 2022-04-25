<?php

namespace App\Entity;

use App\Repository\ArticleBlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleBlogRepository::class)
 */
class ArticleBlog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=CategoryBlog::class, inversedBy="articles")
     */
    private $categoryBlog;

    /**
     * @ORM\OneToMany(targetEntity=ImageBlog::class, mappedBy="articleBlog",cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="datetime")
     */
    private $PostDate;

    

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCategoryBlog(): ?CategoryBlog
    {
        return $this->categoryBlog;
    }

    public function setCategoryBlog(?CategoryBlog $categoryBlog): self
    {
        $this->categoryBlog = $categoryBlog;

        return $this;
    }

    /**
     * @return Collection<int, ImageBlog>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageBlog $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticleBlog($this);
        }

        return $this;
    }

    public function removeImage(ImageBlog $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticleBlog() === $this) {
                $image->setArticleBlog(null);
            }
        }

        return $this;
    }

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->PostDate;
    }

    public function setPostDate(\DateTimeInterface $PostDate): self
    {
        $this->PostDate = $PostDate;

        return $this;
    }

}
