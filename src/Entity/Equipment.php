<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['equipment:read']]),
        new Get(normalizationContext: ['groups' => ['equipment:read:item']]),
        new Post(
            normalizationContext: ['groups' => ['equipment:read:item']],
            denormalizationContext: ['groups' => ['equipment:write']]
        ),
        new Put(
            normalizationContext: ['groups' => ['equipment:read:item']],
            denormalizationContext: ['groups' => ['equipment:write']]
        ),
        new Delete()
    ]
)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['equipment:read', 'equipment:read:item', 'announcement:read:item','announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['equipment:read', 'equipment:read:item', 'equipment:write', 'announcement:read:item','announcement:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['equipment:read:item', 'equipment:write', 'announcement:read:item','announcement:read'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Announcement>
     */
    #[ORM\ManyToMany(targetEntity: Announcement::class, inversedBy: 'equipment')]
    private Collection $announcement;

    public function __construct()
    {
        $this->announcement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function getAnnouncement(): Collection
    {
        return $this->announcement;
    }

    public function addAnnouncement(Announcement $announcement): static
    {
        if (!$this->announcement->contains($announcement)) {
            $this->announcement->add($announcement);
        }

        return $this;
    }

    public function removeAnnouncement(Announcement $announcement): static
    {
        $this->announcement->removeElement($announcement);

        return $this;
    }
}
