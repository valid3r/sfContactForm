<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $from_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $to_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_subject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email_message;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_created;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFromEmail(): ?string
    {
        return $this->from_email;
    }

    public function setFromEmail(?string $from_email): self
    {
        $this->from_email = $from_email;

        return $this;
    }

    public function getToEmail(): ?string
    {
        return $this->to_email;
    }

    public function setToEmail(?string $to_email): self
    {
        $this->to_email = $to_email;

        return $this;
    }

    public function getEmailSubject(): ?string
    {
        return $this->email_subject;
    }

    public function setEmailSubject(?string $email_subject): self
    {
        $this->email_subject = $email_subject;

        return $this;
    }

    public function getEmailMessage(): ?string
    {
        return $this->email_message;
    }

    public function setEmailMessage(?string $email_message): self
    {
        $this->email_message = $email_message;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }
}
