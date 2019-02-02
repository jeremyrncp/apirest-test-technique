<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity(repositoryClass="MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=9, unique=true)
     *
     * @Expose()
     */
    private $imdbID;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Expose()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Expose()
     */
    private $poster;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="Movies")
     *
     * @Exclude()
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getImdbID(): ?string
    {
        return $this->imdbID;
    }

    public function setImdbID(string $imdbID): self
    {
        $this->imdbID = $imdbID;

        return $this;
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

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addMovie($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeMovie($this);
        }

        return $this;
    }
}
