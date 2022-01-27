<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TravailRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=TravailRepository::class)
 * @ApiResource(
 * normalizationContext={
 *      "groups":{"travail_read"}
 * })
 * @ApiFilter(SearchFilter::class, properties={"description":"partial"})
 */
class Travail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"travail_read", "entretien_read", "entretien_subresouce_candidat"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La description du travail est obligatoire!")
     * @Assert\Length(
     *      min=3,
     *      max=254,
     *      minMessage="La description devrait avoir au minimum '{{ limit }}' caractères!",
     *      maxMessage="La description devrait avoir au maximum '{{ limit }}' caractères!"
     * )
     * @Groups({"travail_read", "entretien_read", "entretien_subresouce_candidat"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Entretien::class, mappedBy="travail", orphanRemoval=true)
     * @Groups({"travail_read"})
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $entretien->setTravail($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): self
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getTravail() === $this) {
                $entretien->setTravail(null);
            }
        }

        return $this;
    }
}
