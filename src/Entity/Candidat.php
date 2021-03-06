<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CandidatRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CandidatRepository::class)
 * @ApiResource(
 * attributes={
 *      "order":{"nomComplet":"desc"}
 * },
 * normalizationContext={
 *      "groups":{"candidat_read"}
 * },
 * denormalizationContext={
 *  "disable_type_enforcement"=true
 * })
 * 
 * @ApiFilter(SearchFilter::class, properties={"nomComplet":"partial"})
 * @ApiFilter(OrderFilter::class, properties={"nomComplet"})
 * @UniqueEntity(fields="email", message="email: {{ value }} appartient déjà à un autre candidat!")
 * @UniqueEntity(fields="contact", message="contact: {{ value }} appartient déjà à un autre candidat!")
 * @UniqueEntity(fields="nomComplet", message="{{ value }} Existe déjà dans la base de données!")
 */
class Candidat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"candidat_read", "entretien_read", "travail_read", "entretien_subresouce_travail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire!")
     * @Groups({"candidat_read", "entretien_read", "travail_read", "entretien_subresouce_travail"})
     * @Assert\Type(
     *     type="String",
     *     message="le type du nom n'est pas valide (String)"
     * )
     * @Assert\Length(
     *      min=2,
     *      max=255,
     *      minMessage="Le nom doit avoir au minimum '{{ limit }}' caractères!",
     *      maxMessage="le nom doit avoir au maximum '{{ limit }}' caractères!"
     * )
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La date de naissance est obligatoire!")
     * @Groups({"candidat_read"})
     */
    private $dateNais;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adrèsse est obligatoire!")
     * @Groups({"candidat_read"})
     * @Assert\Type(
     *     type="string",
     *     message="le type de l'adrèsse n'est pas valide (string)"
     * )
     * @Assert\Length(
     *      min=2,
     *      max=255,
     *      minMessage="Un adrèsse doit avoir au minimum '{{ limit }}' caractères!",
     *      maxMessage="Un adrèsse doit avoir au maximum '{{ limit }}' caractères!"
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"candidat_read"})
     *  @Assert\Type(
     *     type="array",
     *     message="les compétences doit être un tableau (array)"
     * )
     */
    private $competences = [];

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le contact est obligatoire!")
     * @Groups({"candidat_read", "entretien_subresouce_travail"})
     * @Assert\Type(
     *     type="string",
     *     message="le type du contact n'est pas valide (string)"
     * )
     * @Assert\Length(
     *      min=2,
     *      max=20,
     *      minMessage="Tel doit avoir au minimum '{{ limit }}' caractères!",
     *      maxMessage="Tel doit avoir au maximum '{{ limit }}' caractères!"
     * )
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'email est obligatoire!")
     * @Email(message="Le format de l'email: {{ value }} n'est pas valide!")
     * @Assert\Length(
     *      min=6,
     *      max=254,
     *      minMessage="Un email doit avoir au minimum '{{ limit }}' caractères!",
     *      maxMessage="Un email doit avoir au maximum '{{ limit }}' caractères!"
     * )
     * @Groups({"candidat_read", "entretien_read", "travail_read", "entretien_subresouce_travail"})
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Entretien::class, mappedBy="candidat", orphanRemoval=true)
     * @ApiSubresource
     */
    private $entretiens;

    public function __construct()
    {
        $this->entretiens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet($nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->dateNais;
    }

    public function setDateNais(\DateTimeInterface $dateNais): self
    {
        $this->dateNais = $dateNais;

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

    public function getCompetences(): ?array
    {
        return $this->competences;
    }

    public function setCompetences($competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Entretien[]
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): self
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens[] = $entretien;
            $entretien->setCandidat($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): self
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getCandidat() === $this) {
                $entretien->setCandidat(null);
            }
        }

        return $this;
    }
}
