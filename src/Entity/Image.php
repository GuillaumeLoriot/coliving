<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['image:read']]),
        new Get(normalizationContext: ['groups' => ['image:read:item']]),
        new Post(
            normalizationContext: ['groups' => ['image:read:item']],
            denormalizationContext: ['groups' => ['image:write']]
        ),
        new Put(
            normalizationContext: ['groups' => ['image:read:item']],
            denormalizationContext: ['groups' => ['image:write']]
        ),
        new Delete()
    ]
)]
#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['image:read', 'image:read:item', 'announcement:read:item','announcement:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['image:read', 'image:read:item', 'image:write', 'announcement:read:item','announcement:read'])]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(inversedBy: 'images', targetEntity: Announcement::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['image:read:item'])]
    private ?Announcement $announcement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getAnnouncement(): ?Announcement
    {
        return $this->announcement;
    }

    public function setAnnouncement(?Announcement $announcement): static
    {
        $this->announcement = $announcement;

        return $this;
    }
}
