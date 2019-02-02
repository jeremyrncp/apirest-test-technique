<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Asserts;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Asserts\NotBlank(message="username must be defined")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=120, unique=true)
     *
     * @Asserts\NotBlank(message="Email must be defined")
     * @Asserts\Email(message="This email isn't valid", mode="html5")
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     *
     * @Asserts\NotBlank(message="Birth date must be defined")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", inversedBy="users")
     * @ORM\JoinTable(
     *     name="movie_choices",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="imdb_id")}
     * )
     */
    private $Movies;

    public function __construct()
    {
        $this->Movies = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->Movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->Movies->contains($movie)) {
            $this->Movies[] = $movie;
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->Movies->contains($movie)) {
            $this->Movies->removeElement($movie);
        }

        return $this;
    }
}
