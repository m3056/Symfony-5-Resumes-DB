<?php

namespace App\Entity;

use App\Repository\SendedResumesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SendedResumesRepository::class)
 */
class SendedResumes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $reaction;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $companyName;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getReaction(): ?string
    {
        return $this->reaction;
    }

    public function setReaction(string $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }
}
