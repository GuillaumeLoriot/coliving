<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['service:read']]),
        new Get(normalizationContext: ['groups' => ['service:read:item']]),
        new Post(
            normalizationContext: ['groups' => ['service:read:item']],
            denormalizationContext: ['groups' => ['service:write']]
        ),
        new Put(
            normalizationContext: ['groups' => ['service:read:item']],
            denormalizationContext: ['groups' => ['service:write']]
        ),
        new Delete()
    ]
)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['service:read', 'service:read:item', 'announcement:read:item','announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['service:read', 'service:read:item', 'service:write', 'announcement:read:item','announcement:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['service:read:item', 'service:write', 'announcement:read:item','announcement:read'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Announcement>
     */
    #[ORM\ManyToMany(targetEntity: Announcement::class, inversedBy: 'services')]
    #[Groups(['service:read:item'])]
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

    public function setDescription(?string $description): static
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
