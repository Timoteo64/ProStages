<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le titre doit être renseigné")
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "Le titre doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le titre doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="La description doit être renseignée")
     * @Assert\Length(
     *      min = 10,
     *      max = 100,
     *      minMessage = "La description doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "La description doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $descMission;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="L'email doit être renseigné")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas valide")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "L'email' doit faire au maximum {{ limit }} caractères"
     * )
     */
    private $emailContact;

    /**
     * @ORM\ManyToMany(targetEntity=Formation::class, inversedBy="stages")
     */
    private $formations;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="stages", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $entreprises;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescMission(): ?string
    {
        return $this->descMission;
    }

    public function setDescMission(string $descMission): self
    {
        $this->descMission = $descMission;

        return $this;
    }

    public function getEmailContact(): ?string
    {
        return $this->emailContact;
    }

    public function setEmailContact(string $emailContact): self
    {
        $this->emailContact = $emailContact;

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        $this->formations->removeElement($formation);

        return $this;
    }

    public function getEntreprises(): ?Entreprise
    {
        return $this->entreprises;
    }

    public function setEntreprises(?Entreprise $entreprises): self
    {
        $this->entreprises = $entreprises;

        return $this;
    }

    public function __toString() {
        return $this->getTitre();
    }
}
