<?php

namespace App\Entity;

use App\Repository\ResumeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ResumeRepository::class)
 */
class Resume
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=1000)
     * 
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $createDate;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $changeDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getCreateDate(): ?string
    {
        return $this->createDate;
    }

    public function setCreateDate(string $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getChangeDate(): ?string
    {
        return $this->changeDate;
    }

    public function setChangeDate(string $changeDate): self
    {
        $this->changeDate = $changeDate;

        return $this;
    }
}
