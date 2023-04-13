<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $email = null;

    #[ORM\Column(length: 180, unique: true )]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy:'idUser',targetEntity: Posts::class)]
    private Collection $posts;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'idUser')]
    #[ORM\JoinColumn(referencedColumnName:'id_event')]
    private Collection $events;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'idUser')]
    #[ORM\JoinColumn(referencedColumnName:'id_message')]
    private Collection $messages;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?int $phone = null;

    #[ORM\Column]
    private ?bool $is_admin = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy:'event')]
    #[ORM\JoinColumn(referencedColumnName: 'id_event')]
    private Collection $id_event;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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


    public function isIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPosts(Posts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setIdUser($this);
        }

        return $this;
    }

    public function removePosts(Posts $posts): self
    {
        if ($this->posts->removeElement($posts)) {
            if ($posts->getIdUser() === $this) {
                $posts->setIdUser(null);
            }
        }

        return $this;
    }


    public function getMessage(): Collection
    {
        return $this->events;
    }

    public function addMessage(Message $messages): self
    {
        if (!$this->messages->contains($messages)) {
            $this->messages->add($messages);
            $messages->addIdUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $messages): self
    {
        if ($this->events->removeElement($messages)) {
            $messages->removeIdUser($this);
        }

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getPassword()
    {
        return $this->password;
    }


    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
