<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    use Trait\IdEntityTrait;
    use Trait\TimestampableEntityTrait;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkedinProfilUrl = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(mappedBy: 'userTargeted', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    /**
     * @var Collection<int, Prospection>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Prospection::class, orphanRemoval: true)]
    private Collection $prospections;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->prospections = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFullname();
    }

    public function getFullname(): string
    {
        return sprintf('%s %s', ucfirst((string) $this->firstname), ucfirst((string) $this->lastname));
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLinkedinProfilUrl(): ?string
    {
        return $this->linkedinProfilUrl;
    }

    public function setLinkedinProfilUrl(string $linkedinProfilUrl): static
    {
        $this->linkedinProfilUrl = $linkedinProfilUrl;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUserTargeted($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUserTargeted() === $this) {
                $notification->setUserTargeted(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prospection>
     */
    public function getProspections(): Collection
    {
        return $this->prospections;
    }

    public function addProspection(Prospection $prospection): static
    {
        if (!$this->prospections->contains($prospection)) {
            $this->prospections->add($prospection);
            $prospection->setUser($this);
        }

        return $this;
    }

    public function removeProspection(Prospection $prospection): static
    {
        if ($this->prospections->removeElement($prospection)) {
            // set the owning side to null (unless already changed)
            if ($prospection->getUser() === $this) {
                $prospection->setUser(null);
            }
        }

        return $this;
    }
}
