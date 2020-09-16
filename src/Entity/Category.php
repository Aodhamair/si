<?php

/**
 * Category entity.
 * This file is part of the Symfony package.
 *
 * (c)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     */
    private $name;

    /**
     * getter for Id
     *
     *
     * @return int|null
     *
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getter for Name
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setter for Name
     * @param string $name
     *
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
