<?php

namespace App\Repository;

use App\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gallery>
 *
 * @method Gallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gallery[]    findAll()
 * @method Gallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

    public function save(Gallery $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Gallery $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdInnerJoinPicture(int $id)
    {
        return $this->createQueryBuilder("gallery")
            ->andWhere("gallery.id = :id")
            ->setParameter('id', $id)
            ->innerJoin("gallery.pictures", "pictures")
            ->addSelect("pictures")
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deletePicture(int $galleryId, int $pictureId)
    {
        return $this->createQueryBuilder("gallery")
            ->andWhere("gallery.id = :gallery_id")
            ->setParameter('gallery_id', $galleryId)
            ->innerJoin("gallery.pictures", "pictures")
            ->addSelect("pictures")
            ->andWhere("pictures.id = :picture_id")
            ->setParameter('picture_id', $pictureId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByIdLeftJoinPicture(int $id)
    {
        return $this->createQueryBuilder("gallery")
            ->andWhere("gallery.id = :id")
            ->setParameter('id', $id)
            ->leftJoin("gallery.pictures", "pictures")
            ->addSelect("pictures")
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
