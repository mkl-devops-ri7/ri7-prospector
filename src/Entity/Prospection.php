<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Enum\ProspectionStatusEnum;
use App\Entity\Enum\ProspectionTypeEnum;
use App\Entity\Trait\IdEntityTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\ProspectionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: ProspectionRepository::class)]
#[ApiResource(security: "is_granted('ROLE_USER')")]
#[Get]
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[GetCollection]
#[Post]
class Prospection implements Stringable
{
    use IdEntityTrait;
    use TimestampableTrait;

    #[ORM\Column(enumType: ProspectionStatusEnum::class)]
    private ProspectionStatusEnum $status = ProspectionStatusEnum::Draft;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    /**
     * @var Collection<int, Action>
     */
    #[ORM\OneToMany(mappedBy: 'prospection', targetEntity: Action::class)]
    private Collection $actions;

    #[ORM\ManyToOne(inversedBy: 'prospections')]
    #[ORM\JoinColumn]
    private ?Contact $contact = null;

    #[ORM\ManyToOne(inversedBy: 'prospections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: ProspectionTypeEnum::class)]
    private ?ProspectionTypeEnum $type = null;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getStatus(): ProspectionStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ProspectionStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): static
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
            $action->setProspection($this);
        }

        return $this;
    }

    public function removeAction(Action $action): static
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getProspection() === $this) {
                $action->setProspection(null);
            }
        }

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?ProspectionTypeEnum
    {
        return $this->type;
    }

    public function setType(ProspectionTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }
}
