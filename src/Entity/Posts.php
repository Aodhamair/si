<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostsRepository")
 */
class Posts
{
    /**
     * Id.
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * title.
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     */
    private $title;

    /**
     * content.
     * @ORM\Column(type="text")
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="1000",
     * )
     */
    private $content;

    /**
     * Created at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * category
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * comments
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="post", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * getter fo Id
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getter for title
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }


    /**
     * setter for title
     * @param string $title
     * @return Posts
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * getter for content
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * setter for content
     * @param string $content
     * @return Posts
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * getter for createdAt
     * @return \DateTimeInterface|null
     */

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * setter for createdAt
     * @param \DateTimeInterface $createdAt
     * @return Posts
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * getter for category
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * setter for category
     * @param Category|null $category
     * @return Posts
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * getter for comments
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Dodawanie komentarza
     * @param Comments $comment
     * @return Posts
     */
    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    /**
     * Usuwanie komentarza
     * @param Comments $comment
     * @return Posts
     */
    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);

            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
