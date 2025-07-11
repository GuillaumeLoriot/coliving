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
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use App\Repository\AnnouncementRepository;
use App\State\AnnouncementPostProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnnouncementRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['announcement:read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['announcement:read:item']]
        ),
        new Post(
            processor: AnnouncementPostProcessor::class,
            denormalizationContext: ['groups' => ['announcement:write']],
            normalizationContext: ['groups' => ['announcement:read:item']],
            security: "is_granted('ROLE_USER')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['announcement:write']],
            normalizationContext: ['groups' => ['announcement:read:item']],
            security: "object.getOwner() == user"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['announcement:write']],
            normalizationContext: ['groups' => ['announcement:read:item']],
            security: "object.getOwner() == user"
        ),
        new Delete(
            security: "object.getOwner() == user"
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'partial',
    'city' => 'partial',
    'zipcode' => 'exact',
    'address' => 'partial',
    'owner.id' => 'exact',
    'services.id' => 'exact',
    'equipment.id' => 'exact',
])]
#[ApiFilter(RangeFilter::class, properties: [
    'dailyPrice',
    'maxClient'
])]
#[ApiFilter(OrderFilter::class, properties: [
    'id',
    'dailyPrice',
    'maxClient',
])]
#[ApiFilter(ExistsFilter::class, properties: [
    'images',
    'services',
    'equipment'
])]
class Announcement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:read:item', 'reservation:read', 'reservation:read:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['announcement:read', 'announcement:read:item', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    #[Groups(['announcement:read', 'announcement:read:item', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?string $description = null;

    #[ORM\Column(length: 150)]
    #[Groups(['announcement:read:item', 'announcement:read', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?string $address = null;

    #[ORM\Column(length: 150)]
    #[Groups(['announcement:read:item', 'announcement:read', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?string $city = null;

    #[ORM\Column(length: 150)]
    #[Groups(['announcement:read:item', 'announcement:read', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?string $zipcode = null;


    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 6)]
    #[Groups(['announcement:read:item', 'announcement:read', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?float $lattitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 6)]
    #[Groups(['announcement:read:item', 'announcement:read', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:read:item', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?int $maxClient = null;

    #[ORM\Column]
    #[Groups(['announcement:read', 'announcement:read:item', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?float $dailyPrice = null;

    #[ORM\Column(length: 500)]
    #[Groups(['announcement:read', 'announcement:read:item', 'announcement:write', 'reservation:read', 'reservation:read:item'])]
    private ?string $imageCover = null;

    #[ORM\ManyToOne(inversedBy: 'announcements', targetEntity: User::class)]
    #[Groups(['announcement:read:item', 'announcement:read', 'reservation:read', 'reservation:read:item'])]
    private ?User $owner = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'announcement', orphanRemoval: true)]
    #[Groups(['announcement:read:item', 'announcement:read'])]
    private Collection $images;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read:item', 'announcement:read'])]
    private Collection $services;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\ManyToMany(targetEntity: Equipment::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read', 'announcement:read:item'])]
    private Collection $equipments;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'announcement')]
    #[Groups(['announcement:read:item'])]
    private Collection $reservations;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->equipments = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    public function getDailyPrice(): ?string
    {
        return $this->dailyPrice;
    }

    public function setDailyPrice(string $dailyPrice): static
    {
        $this->dailyPrice = $dailyPrice;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

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

    public function getLattitude(): ?string
    {
        return $this->lattitude;
    }

    public function setLattitude(string $lattitude): static
    {
        $this->lattitude = $lattitude;

        return $this;
    }

    public function getMaxClient(): ?int
    {
        return $this->maxClient;
    }

    public function setMaxClient(int $maxClient): static
    {
        $this->maxClient = $maxClient;

        return $this;
    }

    public function getImageCover(): ?string
    {
        return $this->imageCover;
    }

    public function setImageCover(string $imageCover): static
    {
        $this->imageCover = $imageCover;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnnouncement($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnouncement() === $this) {
                $image->setAnnouncement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->addAnnouncement($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            $service->removeAnnouncement($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
            $equipment->addAnnouncement($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->equipments->removeElement($equipment)) {
            $equipment->removeAnnouncement($this);
        }

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setAnnouncement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getAnnouncement() === $this) {
                $reservation->setAnnouncement(null);
            }
        }

        return $this;
    }
}
