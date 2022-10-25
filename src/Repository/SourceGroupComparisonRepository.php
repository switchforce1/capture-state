<?php

namespace App\Repository;

use App\Entity\SourceGroupComparison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SourceGroupComparison>
 *
 * @method SourceGroupComparison|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceGroupComparison|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceGroupComparison[]    findAll()
 * @method SourceGroupComparison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceGroupComparisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SourceGroupComparison::class);
    }

    public function add(SourceGroupComparison $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SourceGroupComparison $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SourceGroupComparison[] Returns an array of SourceGroupComparison objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SourceGroupComparison
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
