<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use App\Repository\ReservationRepository;
use App\Entity\UnavailableTimeSlot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['reservation:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            normalizationContext: ['groups' => ['reservation:read:item']],
            security: "object.getClient() == user"
        ),
        new Post(
            normalizationContext: ['groups' => ['reservation:read:item']],
            denormalizationContext: ['groups' => ['reservation:write']],
            security: "is_granted('ROLE_USER')"
        ),
        new Put(
            normalizationContext: ['groups' => ['reservation:read:item']],
            denormalizationContext: ['groups' => ['reservation:write']],
            security: "object.getClient() == user"
        ),
        new Delete(
            security: "object.getClient() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'status' => 'exact',
    'client.id' => 'exact',
    'announcement.id' => 'exact'
])]
#[ApiFilter(RangeFilter::class, properties: [
    'totalAmount',
    'startDate',
    'endDate',
    'created_at'
])]
#[ApiFilter(OrderFilter::class, properties: [
    'id',
    'startDate',
    'endDate',
    'totalAmount',
    'created_at',
    'status'
])]
#[ApiFilter(ExistsFilter::class, properties: [
    'announcement',
    'client'
])]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement:read:item', 'reservation:read', 'reservation:read:item'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['announcement:read:item', 'announcement:read', 'reservation:read', 'reservation:read:item'])]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column]
    #[Groups(['announcement:read:item', 'reservation:read', 'reservation:read:item'])]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(length: 100)]
    #[Groups(['announcement:read:item', 'reservation:read', 'reservation:read:item'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    #[Groups(['announcement:read:item', 'reservation:read', 'reservation:read:item'])]
    private ?string $totalAmount = null;

    #[ORM\Column]
    #[Groups(['announcement:read:item'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'reservations', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['announcement:read:item', 'reservation:read:item', 'reservation:read'])]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read:item', 'reservation:read'])]
    private ?Announcement $announcement = null;

    /**
     * @var Collection<int, UnavailableTimeSlot>
     */
    #[ORM\OneToMany(mappedBy: 'Unavailableslots', targetEntity: UnavailableTimeSlot::class, cascade: ['persist', 'remove'])]
    private Collection $unavailabletimeslots;

    public function __construct()
    {
        $this->unavailabletimeslots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

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

    /**
     * @return Collection<int, UnavailableTimeSlot>
     */
    public function getUnavailabletimeslots(): Collection
    {
        return $this->unavailabletimeslots;
    }

    public function addUnavailabletimeslot(UnavailableTimeSlot $unavailabletimeslot): static
    {
        if (!$this->unavailabletimeslots->contains($unavailabletimeslot)) {
            $this->unavailabletimeslots->add($unavailabletimeslot);
            $unavailabletimeslot->setUnavailableslots($this);
        }

        return $this;
    }

    public function removeUnavailabletimeslot(UnavailableTimeSlot $unavailabletimeslot): static
    {
        if ($this->unavailabletimeslots->removeElement($unavailabletimeslot)) {
            if ($unavailabletimeslot->getUnavailableslots() === $this) {
                $unavailabletimeslot->setUnavailableslots(null);
            }
        }

        return $this;
    }
}
