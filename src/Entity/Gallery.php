<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation\Slug;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class Gallery
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Vous devez donner un titre à votre galerie.")]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: Picture::class, inversedBy: 'galleries', cascade: ["persist"])]
    private Collection $pictures;

    #[ORM\Column(length: 255, unique: true)]
    #[Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\Column]
    private bool $published = false;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Vous devez choisir une image à la une pour votre galerie-- test.")]
    private ?string $cover = null;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        $this->pictures->removeElement($picture);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }
}
