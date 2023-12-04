<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(),
    ],
    normalizationContext: ['groups' => ['contact:read', 'id:read', 'created_at:read']],
    denormalizationContext: ['groups' => ['contact:write']],
)]
#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact implements Stringable
{
    use Trait\IdEntityTrait;
    use Trait\TimestampableEntityTrait;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['contact:read', 'contact:write'])]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Groups(['contact:read', 'contact:write'])]
    #[ORM\Column(length: 255)]
    private ?string $job = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['contact:read', 'contact:write'])]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['contact:read', 'contact:write'])]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Groups(['contact:read', 'contact:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $linkedinProfilUrl = null;

    #[Assert\Blank]
    #[Assert\Length(min: 10, max: 20)]
    #[Groups(['contact:read', 'contact:write'])]
    #[ORM\Column(length: 20)]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, Prospection>
     */
    #[Groups(['contact:read'])]
    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: Prospection::class, orphanRemoval: true)]
    private Collection $prospections;

    #[Groups(['contact:read'])]
    #[ORM\ManyToOne(cascade: ['persist'])]
    private ?Company $company = null;

    public function __construct()
    {
        $this->prospections = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->firstName.' '.$this->lastName;
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

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

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
            $prospection->setContact($this);
        }

        return $this;
    }

    public function removeProspection(Prospection $prospection): static
    {
        if ($this->prospections->removeElement($prospection)) {
            // set the owning side to null (unless already changed)
            if ($prospection->getContact() === $this) {
                $prospection->setContact(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
