<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: WeatherSearchHistory::class)]
    private Collection $weatherSearchHistories;

    public function __construct()
    {
        $this->weatherSearchHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, WeatherSearchHistory>
     */
    public function getWeatherSearchHistories(): Collection
    {
        return $this->weatherSearchHistories;
    }

    public function addWeatherSearchHistory(WeatherSearchHistory $weatherSearchHistory): static
    {
        if (!$this->weatherSearchHistories->contains($weatherSearchHistory)) {
            $this->weatherSearchHistories->add($weatherSearchHistory);
            $weatherSearchHistory->setCity($this);
        }

        return $this;
    }

    public function removeWeatherSearchHistory(WeatherSearchHistory $weatherSearchHistory): static
    {
        if ($this->weatherSearchHistories->removeElement($weatherSearchHistory)) {
            // set the owning side to null (unless already changed)
            if ($weatherSearchHistory->getCity() === $this) {
                $weatherSearchHistory->setCity(null);
            }
        }

        return $this;
    }
}
