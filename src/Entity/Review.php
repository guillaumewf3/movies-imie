<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Your title is too long dude! 255 max!",
     *     min="2",
     *     minMessage="2 chars minimum please!"
     * )
     * @Assert\NotBlank(message="Please provide a title!")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Assert\Length(
     *     max="50",
     *     maxMessage="Your username is too long dude! 50 max!",
     *     min="2",
     *     minMessage="2 chars minimum please!"
     * )
     * @Assert\NotBlank(message="Please provide a username!")
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     *
     * @Assert\Email(message="Your email is not valid!")
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Your email is too long dude! 255 max!",
     *     min="2",
     *     minMessage="2 chars minimum please!"
     * )
     * @Assert\NotBlank(message="Please provide a email!")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     *
     * @Assert\Length(
     *     max="4000",
     *     maxMessage="Your review is too long dude! 4000 max!",
     *     min="2",
     *     minMessage="2 chars minimum please!"
     * )
     * @Assert\NotBlank(message="Please provide a review!")
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = strip_tags($title);

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
