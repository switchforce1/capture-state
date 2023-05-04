<?php

namespace App\Repository\Capture;

use App\Entity\Capture\SourceGroupSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SourceGroupSnapshot>
 *
 * @method SourceGroupSnapshot|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceGroupSnapshot|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceGroupSnapshot[]    findAll()
 * @method SourceGroupSnapshot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceGroupSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SourceGroupSnapshot::class);
    }

    public function add(SourceGroupSnapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SourceGroupSnapshot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SourceGroupSnapshot[] Returns an array of SourceGroupSnapshot objects
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

//    public function findOneBySomeField($value): ?SourceGroupSnapshot
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
