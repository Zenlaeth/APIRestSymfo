<?php

namespace App\Entity;

use App\Entity\Livre;
use App\Entity\Adherent;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *             "method"="GET",
 *             "path"="/prets",
 *             "security"="is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous n'avez pas les droits d'acceder à cette ressource"
 *          },
 *          "post"={
 *              "method"="POST",
 *              "security"="is_granted('ROLE_MANAGER')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource"
 *          }
 * 
 *      },
 *      itemOperations={
 *          "get"={
 *             "method"="GET",
 *             "path"="/prets/{id}",
 *             "security"="(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous ne pouvez avoir accès qu'à vos propres prêts."
 *          },
 *          "put"={
 *             "method"="PUT",
 *             "path"="/prets/{id}",
 *             "security"="(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *             "security_message"="Vous ne pouvez avoir accès qu'à vos propres prêts."
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/prets/{id}",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas les droits d'acceder à cette ressource",
 *          }
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePret;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetourReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherent;

    public function __construct()
    {
        $this->datePret=new \DateTime();
        $dateRetourPrevue=date('Y-m-d H:m:n', strtotime('15 days', $this->getDatePret()->getTimestamp()));
        $dateRetourPrevue=\DateTime::createFromFormat('Y-m-d H:m:n',$dateRetourPrevue);
        $this->dateRetourPrevue=$dateRetourPrevue;
        $this->dateRetourReelle=null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetourReelle(): ?\DateTimeInterface
    {
        return $this->dateRetourReelle;
    }

    public function setDateRetourReelle(?\DateTimeInterface $dateRetourReelle): self
    {
        $this->dateRetourReelle = $dateRetourReelle;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    // /**
    //  * @ORM\PrePersist
    //  *
    //  * @return void
    //  */
    // public function RendIndispoLivre()
    // {
    //     $this->getLivre()->setDispo(false);
    // }
}
