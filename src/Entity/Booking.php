<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\hasLifecycleCallBacks
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="La date d'arrivée doit être au bon format")
     * @Assert\GreaterThan("today", message="la date d'arrivé doit être superieur à  la date de aujourd'hui", groups={"reservation"})
     */
    private $startDate;
    
    /**
     * @ORM\Column(type="datetime")
     *  @Assert\Date(message="La date d'arrivée doit être au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="la date de départ doit être superieur à celle d'arrivé")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $comment;

    /**
     * permet de calculer automatiquement le montant de la reservation et la date de creation de la reservation
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function prepersit()
    {

        if(empty($this->createdAt))
        {
            $date = new \DateTime();
            $this->createdAt = $date;
        }
        
        $this->amount = $this->getNumberDays() * $this->ad->getPrice();

    }

    /**
     * Permet de calculer le nombre de jours entre 2 date
     *
     * @return int
     */
    public function getNumberDays()
    {
        $days = $this->endDate->diff($this->startDate)->d; 
        return $days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    // ------------------ fonctions rajoutés ------------------//


    /**
     * on récupere les jours selectionné lors de la reservation
     *
     * @return array retourne un tableau d'objets de type dateTime des jour qui vienne d'être reservée pour cette annonce
     */
    public function getDays()
    {
        $resultat = range($this->startDate->getTimestamp(), $this->endDate->getTimestamp(), 24*60*60);

        $days = array_map(function($day){
            return new \DateTime(date("y-m-d", $day));
        }, $resultat);

        return $days;


    }

    public function isBookableDate()
    {
        // on récupere les jours qui ne sont pas valide
        $notAvailableDays = $this->ad->getUnavailableDays();

        // on regarde si les jours selectionnés lors de la reservation sont dans les jours indisponibles
        // on recupere les jours reservés
        $days = $this->getDays();

        $bookingDays = array_map(function($day){
            return $day->format("Y-m-d");
        },$days);

        $notAvailable =  array_map(function($day){
            return $day->format("Y-m-d");
        },$notAvailableDays);

        foreach($bookingDays as $bookingDay)
        {
            if(in_array($bookingDay,$notAvailable))
            {
                return false;
            }

        }

        return true;


    }

}
