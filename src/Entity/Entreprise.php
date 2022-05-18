<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="L'activité doit être renseignée")
     * @Assert\Length(
     *      min = 3,
     *      max = 25,
     *      minMessage = "L'activité doit faire au minimum {{ limit }} caractères", 
     *      maxMessage = "L'activité doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $activite;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="L'adresse doit être renseignée")
     * @Assert\Regex("/\d+ (impasse|rue|avenue|place|chemin|boulevard) [a-zA-Z\s]+ \d{5} [a-zA-Z\s]+/")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "L'adresse doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Le nom doit faire au minimum 4 caractères")
     * @Assert\Length(
     *      min = 4,
     *      max = 25,
     *      minMessage = "Le nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le nom doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="L'URL doit être renseignée")
     * @Assert\Url
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Le nom doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $URLsite;

    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="entreprises")
     */
    private $stages;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(string $activite): self
    {
        $this->activite = $activite;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getURLsite(): ?string
    {
        return $this->URLsite;
    }

    public function setURLsite(string $URLsite): self
    {
        $this->URLsite = $URLsite;

        return $this;
    }

    /**
     * @return Collection|Stage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->setEntreprises($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getEntreprises() === $this) {
                $stage->setEntreprises(null);
            }
        }

        return $this;
    }
}
