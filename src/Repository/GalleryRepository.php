<?php

namespace App\Repository;

use App\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function getGalleryInnerJoinPicture(int $id = null, int $pictureId = null)
    {
        $queryBuilder = $this->createQueryBuilder('gallery');
        if ($id) {
            $queryBuilder
                ->andWhere("gallery.id = :id")
                ->setParameter('id', $id);
        }
        $queryBuilder = $this->innerJoinPicture($queryBuilder);

        if ($pictureId) {
            $queryBuilder
                ->andWhere("pictures.id = :picture_id")
                ->setParameter('picture_id', $pictureId);
        }
        $query = $queryBuilder->getQuery();

        if ($id) {
            return $query->getOneOrNullResult();
        }

        return $query->getResult();
    }

    public function getGalleryLeftJoinPicture(int $id = null)
    {
        $queryBuilder = $this->createQueryBuilder('gallery');
        if ($id) {
            $queryBuilder
                ->andWhere("gallery.id = :id")
                ->setParameter('id', $id);
        }
        $queryBuilder = $this->leftJoinPicture($queryBuilder);
        $query = $queryBuilder->getQuery();

        if ($id) {
            return $query->getOneOrNullResult();
        }

        return $query->getResult();
    }

    private function innerJoinPicture(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        $queryBuilder = $queryBuilder ?? $this->getGalleryQueryBuilder();
        $queryBuilder
            ->innerJoin("gallery.pictures", "pictures")
            ->addSelect("pictures");

        return $queryBuilder;
    }

    private function leftJoinPicture(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        $queryBuilder = $queryBuilder ?? $this->getGalleryQueryBuilder();
        $queryBuilder
            ->leftJoin("gallery.pictures", "pictures")
            ->addSelect("pictures");

        return $queryBuilder;
    }

    private function getGalleryQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('gallery');
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
