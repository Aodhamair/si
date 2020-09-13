<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     */
    private $nick;

    /**
     * @ORM\Column(type="string", length=500)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="500",
     * )
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Posts::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * getter for Id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getter for email
     * @return string|null
     */

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setter for email
     * @param string $email
     * @return Comments
     */

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getter for Nick
     * @return string|null
     */

    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * setter for nick
     * @param string $nick
     * @return Comments
     */

    public function setNick(string $nick): self
    {
        $this->nick = $nick;

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
     * @return Comments
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * getter for post
     * @return Posts|null
     */

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    /**
     * setter for post
     * @param Posts|null $post
     * @return Comments
     */

    public function setPost(?Posts $post): self
    {
        $this->post = $post;

        return $this;
    }
}
