<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @UniqueEntity(fields={"username"}, message="This username is not available!")
 * @UniqueEntity(fields={"email"}, message="You already have an account!")
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    //inutile, mais forcée par le UserInterface
    public function getSalt(){return null;}
    public function eraseCredentials(){}

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @Assert\Regex("/(?=.*[a-z])(?=.*[0-9]).{6,}/i")
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="author", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WatchlistItem", mappedBy="user")
     */
    private $watchlistItems;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->watchlistItems = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setAuthor($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WatchlistItem[]
     */
    public function getWatchlistItems(): Collection
    {
        return $this->watchlistItems;
    }

    public function addWatchlistItem(WatchlistItem $watchlistItem): self
    {
        if (!$this->watchlistItems->contains($watchlistItem)) {
            $this->watchlistItems[] = $watchlistItem;
            $watchlistItem->setUser($this);
        }

        return $this;
    }

    public function removeWatchlistItem(WatchlistItem $watchlistItem): self
    {
        if ($this->watchlistItems->contains($watchlistItem)) {
            $this->watchlistItems->removeElement($watchlistItem);
            // set the owning side to null (unless already changed)
            if ($watchlistItem->getUser() === $this) {
                $watchlistItem->setUser(null);
            }
        }

        return $this;
    }
}
