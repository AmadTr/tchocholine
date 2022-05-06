<?php

namespace App\Entity;

use App\Repository\ImageBlogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageBlogRepository::class)
 */
class ImageBlog
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
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=ArticleBlog::class, inversedBy="images")
     */
    private $articleBlog;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getArticleBlog(): ?ArticleBlog
    {
        return $this->articleBlog;
    }

    public function setArticleBlog(?ArticleBlog $articleBlog): self
    {
        $this->articleBlog = $articleBlog;

        return $this;
    }
    public function __toString()
    {
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select        
        // return $this->id;    
    }
}
