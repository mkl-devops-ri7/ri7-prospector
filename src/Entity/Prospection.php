<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Enum\ProspectionStatusEnum;
use App\Entity\Enum\ProspectionTypeEnum;
use App\Repository\ProspectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProspectionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['prospection:read', 'id:read', 'created_at:read']],
    denormalizationContext: ['groups' => ['prospection:write']],
    security: "is_granted('ROLE_USER')",
)]
#[Get]
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[GetCollection]
#[Post]
class Prospection implements Stringable
{
    use Trait\IdEntityTrait;
    use Trait\TimestampableEntityTrait;

    #[Groups(['prospection:read', 'prospection:write'])]
    #[ORM\Column(enumType: ProspectionStatusEnum::class)]
    private ProspectionStatusEnum $status = ProspectionStatusEnum::Draft;

    #[Groups(['prospection:read', 'prospection:write'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    /**
     * @var Collection<int, Action>
     */
    #[Groups(['prospection:read'])]
    #[ORM\OneToMany(mappedBy: 'prospection', targetEntity: Action::class)]
    private Collection $actions;

    #[Groups(['prospection:read', 'prospection:write'])]
    #[ORM\ManyToOne(inversedBy: 'prospections')]
    #[ORM\JoinColumn]
    private ?Contact $contact = null;

    #[Groups(['prospection:read'])]
    #[ORM\ManyToOne(inversedBy: 'prospections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Groups(['prospection:read', 'prospection:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['prospection:read', 'prospection:write'])]
    #[Assert\NotBlank]
    #[ORM\Column(enumType: ProspectionTypeEnum::class)]
    private ?ProspectionTypeEnum $type = null;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
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
