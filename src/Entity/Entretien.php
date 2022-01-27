<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EntretienRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=EntretienRepository::class)
 * @ApiResource(
 * subresourceOperations={
 *      "api_candidats_entretiens_get_subresource"={
 *          "normalization_context"={"groups"={"entretien_subresouce_candidat"}}
 *       },
 *      "api_travails_entretiens_get_subresource"={
 *           "normalization_context"={"groups"={"entretien_subresouce_travail"}}
 *      }
 * },
 *  attributes={
 *      "order":{"date":"desc"}
 * },
 * normalizationContext={
 *  "groups"={"entretien_read"}
 * })
 * 
 * @ApiFilter(SearchFilter::class, properties={"lieu":"partial", "date":"partial"})
 */
class Entretien
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"entretien_read", "travail_read", "entretien_subresouce_candidat", "entretien_subresouce_travail"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="La date de l'entretien est obligatoire!")
     *  @Groups({"entretien_read", "travail_read", "entretien_subresouce_candidat", "entretien_subresouce_travail"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le lieu de l'entretien est obligatoire!")
     * @Assert\Length(
     *      min=3,
     *      max=254,
     *      minMessage="Le lieu devrait avoir au minimum '{{ limit }}' caractères!",
     *      maxMessage="Le Lieu devrait avoir au maximum '{{ limit }}' caractères!"
     * )
     * @Groups({"entretien_read", "travail_read", "entretien_subresouce_candidat", "entretien_subresouce_travail"})
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Candidat::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"entretien_read", "travail_read", "entretien_subresouce_travail"})
     */
    private $candidat;

    /**
     * @ORM\ManyToOne(targetEntity=Travail::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"entretien_read", "entretien_subresouce_candidat"})
     */
    private $travail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getTravail(): ?Travail
    {
        return $this->travail;
    }

    public function setTravail(?Travail $travail): self
    {
        $this->travail = $travail;

        return $this;
    }
}
