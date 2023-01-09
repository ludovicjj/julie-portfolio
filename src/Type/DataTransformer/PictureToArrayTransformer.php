<?php

namespace App\Type\DataTransformer;

use App\Entity\Picture;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PictureToArrayTransformer implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    /**
     * Transforms an object (Collection) to an array.
     * @param Collection $value
     * @return array
     */
    public function transform(mixed $value): array
    {
        if ($value->count() > 0) {
            return $value->map(fn($entity) => (string)$entity->getId())->toArray();
        }

        return [];
    }

    /**
     * @param array $value
     * @return Collection
     */
    public function reverseTransform(mixed $value): Collection
    {
        if (empty($value)) {
            return new ArrayCollection([]);
        }

        $pictures = $this->entityManager->getRepository(Picture::class)->findBy(['id' => $value]);

        return new ArrayCollection($pictures);
    }
}