<?php

namespace App\Entity;

use App\Repository\ClickRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClickRepository::class)]
class Click
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $clickedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $sourceIp = null;

    #[ORM\Column(length: 255)]
    private ?string $referrer = null;

    #[ORM\ManyToOne(targetEntity: Url::class)]
    private ?Url $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClickedAt(): ?\DateTimeInterface
    {
        return $this->clickedAt;
    }

    public function setClickedAt(?\DateTimeInterface $clickedAt): self
    {
        $this->clickedAt = $clickedAt;

        return $this;
    }

    public function getSourceIp(): ?string
    {
        return $this->sourceIp;
    }

    public function setSourceIp(?string $sourceIp): self
    {
        $this->sourceIp = $sourceIp;

        return $this;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function setReferrer(?string $referrer): self
    {
        $this->referrer = $referrer;

        return $this;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function setUrl(?Url $url): self
    {
        $this->url = $url;

        return $this;
    }
}
