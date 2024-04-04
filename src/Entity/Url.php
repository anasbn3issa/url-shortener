<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UrlRepository::class)]
class Url
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2048)]
    private ?string $originalUrl = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $shortCode = null;

    #[ORM\OneToMany(mappedBy: 'url', targetEntity: Click::class)]
    private Collection $clicks;

    public function __construct()
    {
        $this->clicks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function setShortCode(?string $shortCode): self
    {
        $this->shortCode = $shortCode;

        return $this;
    }

    /**
     * @return Collection|Click[]
     */
    public function getClicks(): Collection
    {
        return $this->clicks;
    }

    public function addClick(Click $click): self
    {
        if (!$this->clicks->contains($click)) {
            $this->clicks[] = $click;
            $click->setUrl($this);
        }

        return $this;
    }

    public function removeClick(Click $click): self
    {
        if ($this->clicks->removeElement($click)) {
            // set the owning side to null (unless already changed)
            if ($click->getUrl() === $this) {
                $click->setUrl(null);
            }
        }

        return $this;
    }
}